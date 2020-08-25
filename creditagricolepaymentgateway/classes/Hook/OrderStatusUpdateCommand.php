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

namespace WirecardEE\Prestashop\Classes\Hook;

use Exception;
use \OrderState;

/**
 * class OrderStatusUpdateCommand
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Hook
 */
class OrderStatusUpdateCommand
{
    /** @var int */
    private $orderId;
    /** @var OrderState */
    private $orderState;

    /**
     * OrderStatusPostUpdateCommand constructor.
     * @param OrderState $orderState
     * @param int $orderId
     * @throws Exception
     * @since 2.5.0
     */
    public function __construct($orderState, $orderId)
    {
        if (!(int) $orderId || !is_numeric($orderId)) {
            throw new Exception("orderId is not numeric!");
        }
        $this->orderId = (int) $orderId;
        if (!$orderState instanceof OrderState) {
            throw new Exception("orderState param is not instance of OrderState!");
        }
        $this->orderState = $orderState;
    }

    /**
     * @return int
     * @since 2.5.0
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return OrderState
     * @since 2.5.0
     */
    public function getOrderState()
    {
        return $this->orderState;
    }
}
