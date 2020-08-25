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

namespace WirecardEE\Prestashop\Classes\Notification;

use Wirecard\ExtensionOrderStateModule\Domain\Exception\OrderStateInvalidArgumentException;
use Wirecard\PaymentSdk\Response\FailureResponse;
use WirecardEE\Prestashop\Classes\Service\OrderAmountCalculatorService;
use WirecardEE\Prestashop\Helper\Logger;
use WirecardEE\Prestashop\Helper\Service\OrderService;

/**
 * Class Failure
 * @since 2.1.0
 * @package WirecardEE\Prestashop\Classes\Notification
 */
abstract class Failure
{

    /** @var FailureResponse */
    private $notification;

    /** @var OrderService */
    private $orderService;

    /**
     * @var \WirecardEE\Prestashop\Classes\Service\OrderStateManagerService
     */
    private $orderStateManager;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * FailurePaymentProcessing constructor.
     *
     * @param \Order $order
     * @param FailureResponse $notification
     * @param bool $isPostProcessing
     * @throws OrderStateInvalidArgumentException
     * @since 2.1.0
     */
    public function __construct($order, $notification)
    {
        $this->notification = $notification;
        $this->orderService = new OrderService($order);
        $this->orderStateManager = \Module::getInstanceByName('creditagricolepaymentgateway')->orderStateManager();
        $this->logger = new Logger();
    }

    /**
     * @param $orderStateProcessType
     * @throws \PrestaShopException
     * @since 2.1.0
     */
    protected function processForType($orderStateProcessType)
    {
        $currentState = $this->orderService->getLatestOrderStatusFromHistory();
        $order = $this->orderService->getOrder();
        $nextState = $this->orderStateManager->calculateNextOrderState(
            $currentState,
            $orderStateProcessType,
            $this->notification->getData(),
            new OrderAmountCalculatorService($order)
        );
        if ($nextState) {
            $order->setCurrentState($nextState);
            $order->save();
        }
    }
}
