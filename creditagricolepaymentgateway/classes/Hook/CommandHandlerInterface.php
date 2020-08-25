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

/**
 * Interface CommandHandlerInterface
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Hook
 */
interface CommandHandlerInterface
{
    /**
     * Handle command
     * @since 2.5.0
     */
    public function handle();
}
