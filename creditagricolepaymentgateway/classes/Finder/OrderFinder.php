<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Finder;

/**
 * Class OrderFinder
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Finder
 */
class OrderFinder extends DbFinder
{

    /**
     * @param int $orderId
     * @return Order
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    public function getOrderById($orderId)
    {
        // @TODO: Please use this method in next releases instead of pure SQL query.
        return new \Order($orderId);
    }

    /**
     * @param string $reference
     * @return \ObjectModel|\Order
     * @since 2.5.0
     */
    public function getOrderByReference($reference)
    {
        return (\Order::getByReference($reference))->getFirst();
    }
}
