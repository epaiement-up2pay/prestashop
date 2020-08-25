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

namespace WirecardEE\Prestashop\Classes\Config;

use Wirecard\PaymentSdk\Config\PaymentMethodConfig;

/**
 * Interface ConfigurationFactoryInterface
 *
 * @package WirecardEE\Prestashop\Classes\Config
 * @since 2.1.0
 */
interface ConfigurationFactoryInterface
{
    /**
     * This method should take all necessary steps to return a fully fledged PaymentMethodConfig
     *
     * @return PaymentMethodConfig
     * @since 2.1.0
     */
    public function createConfig();
}
