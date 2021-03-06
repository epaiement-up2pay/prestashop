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

require_once(_PS_MODULE_DIR_.'creditagricolepaymentgateway'.DIRECTORY_SEPARATOR.'vendor'.
    DIRECTORY_SEPARATOR.'wirecard'.DIRECTORY_SEPARATOR.'base-url-matcher'.
    DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'BaseUrlMatcherService.php');

require_once(_PS_MODULE_DIR_.'creditagricolepaymentgateway'.DIRECTORY_SEPARATOR.'helper'
              .DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.'ShopConfigurationService.php');

use Wirecard\BaseUrlMatcher\BaseUrlMatcherService;
use WirecardEE\Prestashop\Helper\Service\ShopConfigurationService;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 *
 * @return bool
 * @since 2.0.0
 */
function upgrade_module_2_0_0()
{
    $creditCardConfig = new ShopConfigurationService('creditcard');

    $baseUrl = $creditCardConfig->getField('base_url');
    $wppUrlDbKey = $creditCardConfig->getFieldName('wpp_url');

    $wppUrl = BaseUrlMatcherService::getWppUrl($baseUrl);
    Configuration::updateGlobalValue($wppUrlDbKey, $wppUrl);

    return true;
}
