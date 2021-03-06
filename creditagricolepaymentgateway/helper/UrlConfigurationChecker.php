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

namespace WirecardEE\Prestashop\Helper;

/**
 * Class UrlConfigurationChecker
 * @package WirecardEE\Prestashop\Helper
 * @since 2.0.0
 */
class UrlConfigurationChecker
{

    /**
     * Checks that both URLs use the same environment (testing/productive)
     *
     * @param string $baseUrl
     * @param string $wppUrl
     *
     * @return bool
     *
     * @since 2.0.0
     */
    public static function isUrlConfigurationValid($baseUrl, $wppUrl)
    {
        $needle = 'test';
        $baseUrlContainsTest = self::checkIfStringContainsSubstring($baseUrl, $needle);
        $wppUrlContainsTest = self::checkIfStringContainsSubstring($wppUrl, $needle);

        return $baseUrlContainsTest === $wppUrlContainsTest;
    }

    /**
     * @param $string
     * @param $needle
     *
     * @return bool
     *
     * @since 2.0.0
     */
    protected static function checkIfStringContainsSubstring($string, $needle)
    {
        if (stripos($string, $needle) === false) {
            return false;
        }

        return true;
    }
}
