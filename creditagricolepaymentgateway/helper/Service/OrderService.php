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

namespace WirecardEE\Prestashop\Helper\Service;

use \Db;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use WirecardEE\Prestashop\Classes\Service\OrderAmountCalculatorService;
use WirecardEE\Prestashop\Helper\OrderManager;

/**
 * Class OrderService
 * @package WirecardEE\Prestashop\Helper\Service
 * @since 2.1.0
 */
class OrderService
{
    /** @var \Order */
    private $order;

    /** @var OrderAmountCalculatorService $orderAmountCalculatorService */
    private $orderAmountCalculatorService;

    /**
     * OrderService constructor.
     *
     * @param \Order $order
     * @since 2.1.0
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * @param OrderAmountCalculatorService $orderAmountCalculatorService
     */
    public function setOrderAmountCalculatorService(OrderAmountCalculatorService $orderAmountCalculatorService)
    {
        $this->orderAmountCalculatorService = $orderAmountCalculatorService;
    }

    /**
     * @param SuccessResponse $response
     * @param float $amount
     *
     * @return bool
     * @since 2.10.0
     */
    public function createOrderPayment(SuccessResponse $response, $amount)
    {
        if (!$this->orderAmountCalculatorService) {
            return false;
        }
        $flownAmount = $this->flownAmount(
            $amount,
            $response->getParentTransactionId()
        );
        if ($flownAmount) {
            return $this->order->addOrderPayment(
                (float)$flownAmount,
                null,
                $response->getTransactionId()
            );
        }
        return false;
    }

    /**
     * @param float $requestedAmount
     * @param string $parentTransactionId
     *
     * @return float
     * @since 2.10.0
     */
    private function flownAmount($requestedAmount, $parentTransactionId)
    {
        $orderState = $this->order->current_state;
        switch ($orderState) {
            case \Configuration::get('PS_OS_REFUND'):
            case \Configuration::get(OrderManager::WIRECARD_OS_PARTIALLY_REFUNDED):
                return $requestedAmount * -1;
            case \Configuration::get(OrderManager::WIRECARD_OS_PARTIALLY_CAPTURED):
                $amount = $requestedAmount;
                if ($this->isTransactionRefund($parentTransactionId)) {
                    $amount = $requestedAmount * -1;
                }
                return $amount;
            default:
                return 0.0;
        }
    }

    /**
     * @param string $transactionId
     *
     * @return bool
     * @since 2.10.0
     */
    private function isTransactionRefund($transactionId)
    {
        $isRefund = false;
        if ($this->orderAmountCalculatorService->getOrderRefundedAmount($transactionId) > 0) {
            $isRefund = true;
        }
        return $isRefund;
    }

    /**
     * @param string $transaction_id
     *
     * @param float $amount
     *
     * @since 2.1.0
     */
    public function updateOrderPayment($transaction_id, $amount)
    {
        $order_payments = \OrderPayment::getByOrderReference($this->order->reference);
        $last_index = count($order_payments) - 1;
        $order_current_state = (int) $this->order->getCurrentState();
        $order_payment_state = (int) \Configuration::get('PS_OS_PAYMENT');

        if (!empty($order_payments)&&($order_current_state === $order_payment_state)) {
            $order_payments[$last_index]->transaction_id = $transaction_id;
            $order_payments[$last_index]->amount = $amount;
            $order_payments[$last_index]->save();
        }
    }


    /**
     * @param string $orderReference
     *
     * @return boolean
     * @throws \PrestaShopDatabaseException
     * @since 2.10.0
     */
    public function deleteOrderPayment($orderReference)
    {
        return Db::getInstance()->execute(
            'DELETE
                FROM `' . _DB_PREFIX_ . 'order_payment`
                WHERE `order_reference` = \'' . pSQL($orderReference) . '\''
        );
    }


    /**
     * @param string $order_state
     *
     * @return boolean
     * @since 2.1.0
     */
    public function isOrderState($order_state)
    {
        $order_state = \Configuration::get($order_state);
        return $this->order->current_state === $order_state;
    }

    /**
     * @return \Cart
     * @since 2.1.0
     */
    public function getOrderCart()
    {
        return \Cart::getCartByOrderId($this->order->id);
    }

    /**
     * @return \Cart
     * @since 2.1.0
     */
    public function getNewCartDuplicate()
    {
        $original_cart = $this->getOrderCart();
        return $original_cart->duplicate()['cart'];
    }

    /**
     * @param int $lang
     *
     * @return string
     * @since 2.7.0
     */
    public function getLatestOrderStatusFromHistory($lang = null)
    {
        $order = new \Order((int) $this->order->id);
        $order_history = $order->getHistory($lang);
        $order_status_latest = array_shift($order_history);
        $order_status = $order_status_latest['id_order_state'];

        return $order_status;
    }

    /**
     * @return \Order
     */
    public function getOrder(): \Order
    {
        return $this->order;
    }
}
