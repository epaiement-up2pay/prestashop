<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials;

/**
 * Class PaymentMethodRegistry
 * @package Credentials
 * @since 1.0.0
 */
class PaymentMethodRegistry
{
    /**
     * @var string
     */
    const TYPE_CREDIT_CARD = "creditcard";
    /**
     * @var string
     */
    const TYPE_PAYPAL = "paypal";
    /**
     * @var string
     */
    const TYPE_SOFORTBANKING = "sofortbanking";
    /**
     * @var string
     */
    const TYPE_ALIPAY_XBORDER = "alipay-xborder";
    /**
     * @var string
     */
    const TYPE_IDEAL = "ideal";
    /**
     * @var string
     */
    const TYPE_WIRETRANSFER = "wiretransfer";
    /**
     * @var string
     */
    const TYPE_RATEPAY = "ratepay-invoice";
    /**
     * @var string
     */
    const TYPE_EPS = "eps";
    /**
     * @var string
     */
    const TYPE_GIROPAY = "giropay";
    /**
     * @var string
     */
    const TYPE_ZAPP = "zapp";
    /**
     * @var string
     */
    const TYPE_SEPACREDIT = "sepacredit";
    /**
     * @var string
     */
    const TYPE_SEPA_DIRECT_DEBIT = "sepadirectdebit";
    /**
     * @var string
     */
    const TYPE_MASTERPASS = "masterpass";
    /**
     * @var string
     */
    const TYPE_P24 = "p24";

    /**
     * @return array
     * @since 1.0.0
     */
    public static function availablePaymentMethods()
    {
        return [
            self::TYPE_CREDIT_CARD,
            self::TYPE_PAYPAL,
            self::TYPE_SOFORTBANKING,
            self::TYPE_ALIPAY_XBORDER,
            self::TYPE_IDEAL,
            self::TYPE_WIRETRANSFER,
            self::TYPE_RATEPAY,
            self::TYPE_EPS,
            self::TYPE_GIROPAY,
            self::TYPE_ZAPP,
            self::TYPE_SEPACREDIT,
            self::TYPE_SEPA_DIRECT_DEBIT,
            self::TYPE_MASTERPASS,
            self::TYPE_P24,
        ];
    }
}
