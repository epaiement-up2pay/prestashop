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

namespace WirecardEE\Prestashop\Classes\Engine;

use Wirecard\PaymentSdk\Response\Response;

/**
 * Class ReturnResponse
 *
 * @package WirecardEE\Prestashop\Classes\Engine
 * @since 2.1.0
 */
final class ReturnResponse extends PaymentSdkResponse
{
    /**
     * @param array $response
     * @return Response|false
     * @since 2.1.0
     */
    public function process($response)
    {
        parent::process($response);

        return $this->backend_service->handleResponse($response);
    }
}
