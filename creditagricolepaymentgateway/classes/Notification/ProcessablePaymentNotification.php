<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Notification;

/**
 * Interface ProcessablePaymentNotification
 * @package WirecardEE\Prestashop\Classes\Notification
 * @since 2.1.0
 */
interface ProcessablePaymentNotification
{
    /**
     * @since 2.1.0
     */
    public function process();
}
