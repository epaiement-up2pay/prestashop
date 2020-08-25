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

namespace WirecardEE\Prestashop\Classes\Notification\Initial;

use Wirecard\ExtensionOrderStateModule\Domain\Entity\Constant;
use WirecardEE\Prestashop\Classes\Notification\ProcessablePaymentNotification;

class Failure extends \WirecardEE\Prestashop\Classes\Notification\Failure implements ProcessablePaymentNotification
{

    /**
     * @since 2.1.0
     */
    public function process()
    {
        $this->processForType(Constant::PROCESS_TYPE_INITIAL_NOTIFICATION);
    }
}
