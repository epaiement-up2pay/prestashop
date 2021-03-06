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

namespace WirecardEE\Prestashop\Classes\Constants;

/**
 * Class ConfigConstants
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Config
 */
class ConfigConstants
{
    /** @var string */
    const SETTING_GENERAL_AUTOMATIC_CAPTURE_ENABLED =
        'WIRECARD_PAYMENT_GATEWAY_GENERAL_AUTOMATIC_CAPTURE_ENABLED';
    /** @var string */
    const SETTING_GENERAL_FORCE_ORDER_STATE_CHANGE_ENABLED =
        'WIRECARD_PAYMENT_GATEWAY_GENERAL_FORCE_ORDER_STATE_CHANGE_ENABLED';
    /** @var array  */
    const SETTING_SUFFIX_WHITELIST = [
        // Default settings used in all payment methods
        '_ENABLED',
        '_TITLE',
        '_MERCHANT_ACCOUNT_ID',
        '_BASE_URL',
        '_WPP_URL',
        '_PAYMENT_ACTION',
        '_DESCRIPTOR',
        '_SEND_ADDITIONAL',
        '_TEST_CREDENTIALS',

        // Special settings for certain payment methods
        '_CREDITCARD_THREE_D_MERCHANT_ACCOUNT_ID',
        '_CREDITCARD_SSL_MAX_LIMIT',
        '_CREDITCARD_THREE_D_MIN_LIMIT',
        '_CREDITCARD_CCVAULT_ENABLED',
        '_CREDITCARD_REQUESTOR_CHALLENGE',

        '_PAYPAL_SHOPPING_BASKET',

        '_SEPADIRECTDEBIT_CREDITOR_CITY',
        '_SEPADIRECTDEBIT_SEPADIRECTDEBIT_TEXTEXTRA',
        '_SEPADIRECTDEBIT_ENABLE_BIC',

        '_INVOICE_BILLINGSHIPPING_SAME',
        '_INVOICE_SHIPPING_COUNTRIES',
        '_INVOICE_BILLING_COUNTRIES',
        '_INVOICE_ALLOWED_CURRENCIES',
        '_INVOICE_AMOUNT_MIN',
        '_INVOICE_AMOUNT_MAX',

        '_POIPIA_PAYMENT_TYPE',
    ];
}
