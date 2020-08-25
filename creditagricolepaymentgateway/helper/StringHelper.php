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

use Tools;

/**
 * Class StringHelper
 * @package WirecardEE\Prestashop\Helper
 * @since 2.5.0
 */
class StringHelper
{
    /**
     * string with specified prefix
     * @param string $value
     * @param string $prefix
     * @return string
     * @since 2.5.0
     */
    public static function startFrom($value, $prefix)
    {
        return (string)Tools::substr($value, strpos($value, $prefix) + Tools::strlen($prefix));
    }

    /**
     * @param string $value
     * @param array $searchList
     * @param array $replacementList
     * @return string
     * @since 2.5.0
     */
    public static function replaceWith($value, $searchList = ['-'], $replacementList = ['_'])
    {
        return (string) str_replace($searchList, $replacementList, $value);
    }
}
