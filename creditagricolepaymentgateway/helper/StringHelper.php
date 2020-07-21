<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Helper;

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
        $newString = substr($value, strpos($value, $prefix) + strlen($prefix));
        return strval($newString);
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
        return strval(str_replace($searchList, $replacementList, $value));
    }
}
