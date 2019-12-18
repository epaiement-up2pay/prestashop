<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Constants;

/**
 * Class FormConstants
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Constants
 */
class FormConstants
{
    /** @var string  */
    const FORM_GROUP_TYPE_INPUT = "input";
    /** @var string  */
    const FORM_GROUP_TYPE_SUBMIT = "submit";
    /** @var string  */
    const FORM_ELEMENT_TYPE_SWITCH = "switch";
    /** @var string  */
    const FORM_ELEMENT_TYPE_SUBMIT = "submit";

    /**
     * @return array
     * @since 2.5.0
     */
    public static function getGroupTypesWithChildren()
    {
        return [
            self::FORM_GROUP_TYPE_INPUT
        ];
    }

    /**
     * @return array
     * @since 2.5.0
     */
    public static function getElementTypesWithValues()
    {
        return [
            self::FORM_ELEMENT_TYPE_SWITCH
        ];
    }
}
