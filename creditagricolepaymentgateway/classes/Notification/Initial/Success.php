<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Notification\Initial;

use WirecardEE\Prestashop\Classes\Notification\ProcessablePaymentNotification;
use \WirecardEE\Prestashop\Classes\Notification\Success as AbstractSuccess;

class Success extends AbstractSuccess implements ProcessablePaymentNotification
{
    public function process()
    {
        return parent::process();
    }
}
