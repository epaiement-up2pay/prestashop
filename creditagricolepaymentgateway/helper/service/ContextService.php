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

namespace WirecardEE\Prestashop\Helper\Service;

use Wirecard\PaymentSdk\Response\SuccessResponse;

/**
 * Class ContextService
 * @since 2.1.0
 *@package WirecardEE\Prestashop\Helper\Service
 */
class ContextService
{
    /** @var \Context */
    private $context;

    /**
     * ContextService constructor.
     *
     * @param \Context $context
     * @since 2.1.0
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * @param \Cart $cart
     * @since 2.1.0
     */
    public function setCart($cart)
    {
        $this->context->cart = $cart;
        $this->context->id_cart = $cart->id;
        $this->context->cookie->id_cart = $cart->id;
    }

    /**
     * @param array $errors
     * @param string $controller_name
     * @since 2.1.0
     */
    public function redirectWithError($errors, $controller_name)
    {
        $this->context->controller->errors = $errors;
        $this->context->controller->redirectWithNotifications($this->context->link->getPageLink($controller_name));
    }

    /**
     * @param string $url
     * @since 2.1.0
     */
    public function redirectWithNotification($url)
    {
        $this->context->controller->redirectWithNotifications($url);
    }

    /**
     * @param string $template_path
     * @param array $data
     * @since 2.1.0
     */
    public function showTemplateWithData($template_path, $data)
    {
        $this->context->smarty->assign($data);
        $this->context->smarty->display($template_path);
    }

    /**
     * @param SuccessResponse $response
     * @since 2.1.0
     */
    public function setPiaCookie($response)
    {
        $data = $response->getData();

        $this->context->cookie->__set('pia-enabled', true);
        $this->context->cookie->__set('pia-iban', $data['merchant-bank-account.0.iban']);
        $this->context->cookie->__set('pia-bic', $data['merchant-bank-account.0.bic']);
        $this->context->cookie->__set('pia-reference-id', $data['provider-transaction-reference-id']);
    }
}
