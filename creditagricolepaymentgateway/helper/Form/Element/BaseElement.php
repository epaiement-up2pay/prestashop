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

namespace WirecardEE\Prestashop\Helper\Form\Element;

use Exception;
use WirecardEE\Prestashop\Helper\Form\FormElementInterface;
use WirecardEE\Prestashop\Helper\OptionHelper;

/**
 * Class BaseElement
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Helper\Form\Element
 */
abstract class BaseElement implements FormElementInterface
{
    /** @var string */
    private $name;

    /** @var string */
    private $label;

    /** @var OptionHelper */
    protected $optionHelper;

    /**
     * @return string
     * @since 2.5.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @since 2.5.0
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     * @since 2.5.0
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @since 2.5.0
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     * @since 2.5.0
     */
    public function generateUniqueId()
    {
        return $this->getName() . "_" . $this->getType() . "_" . uniqid();
    }

    /**
     * BaseElement constructor.
     * @param string $name
     * @param string $label
     * @throws Exception
     * @since 2.5.0
     */
    public function __construct($name, $label)
    {
        if (empty($name) || empty($label)) {
            throw new Exception("Either 'name' or 'label' property is missing or invalid.");
        }

        $this->optionHelper = new OptionHelper();
        $this->setName($name);
        $this->setLabel($label);
        $this->optionHelper->addOption('type', $this->getType());
        $this->optionHelper->addOption('name', $this->getName());
    }

    /**
     * @return array
     * @since 2.5.0
     */
    public function build()
    {
        return $this->optionHelper->getOptions();
    }
}
