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

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param CreditAgricolePaymentGateway $module
 *
 * @return bool
 * @throws PrestaShopDatabaseException
 * @since 1.3.5
 */
function upgrade_module_1_3_5($module)
{
    $module->addMissingColumns('cc');

    $table = '`' . _DB_PREFIX_ . 'wirecard_payment_gateway_cc`';
    $module->executeSql("ALTER TABLE $table ADD INDEX user_address (user_id, address_id)", 1061);

    return true;
}
