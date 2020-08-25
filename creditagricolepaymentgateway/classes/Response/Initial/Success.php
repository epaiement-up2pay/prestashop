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

namespace WirecardEE\Prestashop\Classes\Response\Initial;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Transaction\Transaction as TransactionTypes;
use WirecardEE\Prestashop\Classes\Response\Success as SuccessAbstract;
use WirecardEE\Prestashop\Classes\Service\OrderAmountCalculatorService;
use WirecardEE\Prestashop\Helper\OrderManager;
use WirecardEE\Prestashop\Helper\Service\ContextService;
use WirecardEE\Prestashop\Helper\Service\ShopConfigurationService;
use WirecardEE\Prestashop\Models\PaymentPoiPia;

class Success extends SuccessAbstract
{
    /**
     * @var ContextService
     */
    private $context_service;

    /**
     * @var \Cart
     */
    private $cart;

    /**
     * @var \Customer
     */
    private $customer;

    /**
     * @var \CreditAgricolePaymentGateway
     */
    private $module;

    /**
     * @var \WirecardEE\Prestashop\Classes\Service\OrderStateManagerService
     */
    private $orderStateManager;

    /**
     * Success constructor.
     * @param $order
     * @param $response
     * @throws \Wirecard\ExtensionOrderStateModule\Domain\Exception\NotInRegistryException
     * @throws OrderStateInvalidArgumentException
     * @since 2.5.0
     */
    public function __construct($order, $response)
    {
        parent::__construct($order, $response);

        $this->context_service = new ContextService(\Context::getContext());
        $this->cart = $this->orderService->getOrderCart();
        $this->customer = new \Customer((int)$this->cart->id_customer);
        $this->module = \Module::getInstanceByName('creditagricolepaymentgateway');
        $this->orderStateManager = $this->module->orderStateManager();
    }

    /**
     * @since 2.10.0
     */
    protected function beforeProcess()
    {
        $order_status = $this->orderService->getLatestOrderStatusFromHistory();
        $nextState = $this->orderStateManager->calculateNextOrderState(
            $order_status,
            Constant::PROCESS_TYPE_INITIAL_RETURN,
            $this->response->getData(),
            new OrderAmountCalculatorService($this->order)
        );
        if ($nextState) {
            $this->order->setCurrentState($nextState);
            $this->order->save();
        }
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess()
    {
        if ($this->isPiaPayment()) {
            $this->context_service->setPiaCookie($this->response);
        }
        //redirect to success page
        \Tools::redirect(
            'index.php?controller=order-confirmation&id_cart='
            . $this->cart->id . '&id_module='
            . $this->module->id . '&id_order='
            . $this->order->id . '&key='
            . $this->customer->secure_key
        );
    }

    /**
     * Check if the payment method is PoiPia and the payment type is Pia
     *
     * @return bool
     * @since 2.5.0
     */
    private function isPiaPayment()
    {
        $configuration_service = new ShopConfigurationService(PaymentPoiPia::TYPE);
        return $this->response->getPaymentMethod() === PaymentPoiPia::TYPE &&
            $configuration_service->getField('payment_type') === PaymentPoiPia::PIA;
    }
}
