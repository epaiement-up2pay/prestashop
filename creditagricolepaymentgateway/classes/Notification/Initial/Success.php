<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Notification\Initial;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use WirecardEE\Prestashop\Classes\Notification\Success as AbstractSuccess;

/**
 * Class Success
 * @package WirecardEE\Prestashop\Classes\Notification\Initial
 */
class Success extends AbstractSuccess
{
    /**
     * @inheritDoc
     */
    public function getOrderStateProcessType()
    {
        return Constant::PROCESS_TYPE_INITIAL_NOTIFICATION;
    }
}
