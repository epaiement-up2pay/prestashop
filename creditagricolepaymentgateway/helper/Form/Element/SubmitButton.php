<?php

/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Helper\Form\Element;

use WirecardEE\Prestashop\Classes\Constants\FormConstants;

/**
 * Class SubmitButton
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Helper\Form\Element
 */
class SubmitButton extends BaseElement
{
    /**
     * @return string
     * @since 2.5.0
     */
    public function getType()
    {
        return FormConstants::FORM_ELEMENT_TYPE_SUBMIT;
    }

    /**
     * @return string
     * @since 2.5.0
     */
    public function getGroup()
    {
        return FormConstants::FORM_GROUP_TYPE_SUBMIT;
    }

    /**
     * @return array
     * @since 2.5.0
     */
    public function build()
    {
        parent::build();
        $this->addOption('title', $this->getLabel());
        return $this->getOptions();
    }
}
