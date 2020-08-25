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
 * Class StringHelper
 * @package WirecardEE\Prestashop\Helper
 * @since 2.5.0
 */
class ArrayHelper
{
    /**
     * Filters array trough items starting with specified prefix
     * @param array $data
     * @param string $prefix
     * @return array
     * @since 2.5.0
     */
    public static function filterWithPrefix($data, $prefix)
    {
        $filteredData = [];

        foreach ($data as $paramName => $value) {
            if (strpos($paramName, $prefix) !== false) {
                $filteredData[$paramName] = $value;
            }
        }

        return $filteredData;
    }
}
