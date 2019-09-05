<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Crédit Agricole and are explicitly not part
 * of the Crédit Agricole range of products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License version 3 (GPLv3) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Crédit Agricole does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Crédit Agricole does not guarantee their full
 * functionality neither does Crédit Agricole assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Crédit Agricole does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 *
 * @author Crédit Agricole
 * @copyright Crédit Agricole
 * @license GPLv3
 */

use WirecardEE\Prestashop\Classes\Engine\ReturnResponse;
use WirecardEE\Prestashop\Classes\Response\ProcessablePaymentResponseFactory;
use WirecardEE\Prestashop\Classes\Response\Cancel;
use WirecardEE\Prestashop\Helper\Logger as WirecardLogger;

/**
 * Class CreditAgricolePaymentGatewayReturnModuleFrontController
 *
 * @extends ModuleFrontController
 * @property CreditAgricolePaymentGateway module
 *
 * @since 1.0.0
 */
class CreditAgricolePaymentGatewayReturnModuleFrontController extends ModuleFrontController
{
    const CANCEL_PAYMENT_STATE = 'cancel';

    /** @var WirecardLogger  */
    private $logger;

    /**
     * CreditAgricolePaymentGatewayReturnModuleFrontController constructor.
     * @since 2.1.0
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger = new WirecardLogger();
    }

    /**
     * Process redirects and responses
     *
     * @since 1.0.0
     */
    public function postProcess()
    {
        $response = \Tools::getAllValues();
        $order_id = \Tools::getValue('id_order');
        $payment_state = \Tools::getValue('payment_state');

        try {
            $order = new Order((int) $order_id);

            if ($payment_state !== Cancel::CANCEL_PAYMENT_STATE) {
                $response = $this->processRawResponse($response);
            }

            $response_factory = new ProcessablePaymentResponseFactory($response, $order, $payment_state);
            $processing_strategy = $response_factory->getResponseProcessing();
            $processing_strategy->process();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Error in class:'. __CLASS__ .
                ' method:' . __METHOD__ .
                ' exception: ' . $exception->getMessage()
            );
            $this->errors = $exception->getMessage();
            $this->redirectWithNotifications($this->context->link->getPageLink('order'));
        }
    }

    /**
     * @param $response
     *
     * @return false|\Wirecard\PaymentSdk\Response\Response
     * @since 2.1.0
     */
    private function processRawResponse($response)
    {
        $engine_processing = new ReturnResponse();
        return $engine_processing->process($response);
    }
}