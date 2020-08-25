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

namespace WirecardEE\Prestashop\Classes\Response;

use Wirecard\PaymentSdk\Response\InteractionResponse;

/**
 * Class Redirect
 * @package WirecardEE\Prestashop\Classes\Response
 * @since 2.1.0
 */
final class Redirect implements ProcessablePaymentResponse
{
    /** @var InteractionResponse  */
    private $response;

    /**
     * InteractionResponseProcessing constructor.
     *
     * @param InteractionResponse $response
     * @since 2.1.0
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @since 2.1.0
     */
    public function process()
    {
        \Tools::redirect($this->response->getRedirectUrl());
    }
}
