<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 * @author Crédit Agricole
 * @copyright Copyright (c) 2020 Crédit Agricole, Einsteinring 35, 85609 Aschheim, Germany
 * @license MIT License
 */

use WirecardEE\Prestashop\Classes\Controller\WirecardFrontController;
use WirecardEE\Prestashop\Classes\Engine\NotificationResponse;
use WirecardEE\Prestashop\Classes\Notification\ProcessablePaymentNotificationFactory;
use WirecardEE\Prestashop\Helper\Logger as WirecardLogger;

class CreditAgricolePaymentGatewayNotifyModuleFrontController extends WirecardFrontController
{
    /** @var WirecardLogger  */
    public $logger;

    /**
     * CreditAgricolePaymentGatewayNotifyModuleFrontController constructor.
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
        $notification = \Tools::file_get_contents('php://input');
        try {
            $order = $this->getOrder();

            $engine_processing = new NotificationResponse();
            $processed_notify = $engine_processing->process($notification);

            $notify_factory = new ProcessablePaymentNotificationFactory($order, $processed_notify);
            $payment_processing = $notify_factory->getPaymentProcessing();
            $payment_processing->process();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Error in class:'. __CLASS__ .
                ' method:' . __METHOD__ .
                ' exception: ' . $exception->getMessage()
            );
        }
    }

    /**
     * @return Order
     * @throws \Exception
     * @since 2.1.0
     */
    private function getOrder()
    {
        $order_id = \Tools::getValue('id_order');
        if (\Tools::getValue('payment_type') === 'creditcard') {
            $order_id = $this->getOrderFromCart();
        }

        return new \Order((int) $order_id);
    }

    /**
     * @param int $tick
     * @return int
     * @throws \Exception
     * @since 2.1.0
     */
    private function getOrderFromCart($tick = 1)
    {
        $cart_id = \Tools::getValue('id_cart');
        if ($tick > 30) {
            throw new \Exception('Order with cart id '. $cart_id .' was not mappable');
        }

        /** @var false|int $order_id */
        $order_id = \Order::getIdByCartId($cart_id);

        if ($order_id) {
            return $order_id;
        }

        sleep(1);
        return $this->getOrderFromCart();
    }
}
