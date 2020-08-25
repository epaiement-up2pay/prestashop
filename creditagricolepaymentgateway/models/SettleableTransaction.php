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

namespace WirecardEE\Prestashop\Models;

use Wirecard\PaymentSdk\Response\SuccessResponse;
use WirecardEE\Prestashop\Helper\OrderManager;
use WirecardEE\Prestashop\Helper\Service\OrderService;

interface SettleableTransaction
{

    /**
     * Get the remaining amount from the already processed amount to the total amount (amount).
     *
     * @return float
     */
    public function getRemainingAmount();

    /**
     * Get the total amount. regardless of whether it has been processed or not.
     *
     * @return float
     */
    public function getAmount();

    /**
     * Update the order according to the newest processed state of the transaction.
     *
     * @param \Order $order
     * @param SuccessResponse $notification
     * @param OrderManager $orderManager
     * @param OrderService $orderService
     * @return bool
     */
    public function updateOrder(
        \Order $order,
        SuccessResponse $notification,
        OrderManager $orderManager,
        OrderService $orderService
    );
}
