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

namespace WirecardEE\Prestashop\Classes\Transaction\Entity\Cart;

use Wirecard\PaymentSdk\Entity\Amount;

/**
 * Interface CartItemInterface
 * @package WirecardEE\Prestashop\Classes\Transaction\Entity\Cart
 * @since 2.5.0
 */
interface CartItemInterface
{
    /**
     * @return string
     * @since 2.5.0
     */
    public function getName();

    /**
     * @return Amount
     * @since 2.5.0
     */
    public function getAmount();

    /**
     * @return int
     * @since 2.5.0
     */
    public function getQuantity();

    /**
     * @return string
     * @since 2.5.0
     */
    public function getShortDescription();

    /**
     * @return string
     * @since 2.5.0
     */
    public function getProductReference();

    /**
     * @return Amount
     * @since 2.5.0
     */
    public function getTaxAmount();

    /**
     * @return float
     * @since 2.5.0
     */
    public function getTaxRate();
}
