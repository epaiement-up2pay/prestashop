<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Response;

use Wirecard\PaymentSdk\Response\Response;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Response\InteractionResponse;
use Wirecard\PaymentSdk\Response\FormInteractionResponse;
use Wirecard\PaymentSdk\Response\FailureResponse;
use WirecardEE\Prestashop\Classes\Response\Initial\Success as InitialSuccess;
use WirecardEE\Prestashop\Classes\Response\PostProcessing\Success as PostProcessingSuccess;
use WirecardEE\Prestashop\Classes\ProcessType;

/**
 * Class ProcessablePaymentResponseFactory
 * @package WirecardEE\Prestashop\Classes\Response
 * @since 2.1.0
 */
class ProcessablePaymentResponseFactory
{
    /** @var SuccessResponse|FailureResponse|InteractionResponse|FormInteractionResponse */
    private $response;

    /** @var \Order  */
    private $order;

    /** @var string */
    private $orderState;

    /** @var string */
    private $processType;

    /**
     * ResponseProcessingFactory constructor.
     *
     * @param Response $response
     * @param \Order $order
     * @param string $processType
     * @param string $orderState
     * @since 2.1.0
     */
    public function __construct($response, $order, $processType = ProcessType::PROCESS_RESPONSE, $orderState = null)
    {
        $this->order = $order;
        $this->orderState = $orderState;
        $this->processType = $processType;
        $this->response = $response;
    }

    /**
     * @return ProcessablePaymentResponse
     * @since 2.1.0
     */
    public function getResponseProcessing()
    {
        if ($this->isCancelResponse($this->orderState)) {
            return new Cancel($this->order);
        }

        switch (true) {
            case $this->response instanceof SuccessResponse:
                if ($this->processType === ProcessType::PROCESS_RESPONSE) {
                    return new InitialSuccess($this->order, $this->response);
                }

                return new PostProcessingSuccess($this->order, $this->response);
            case $this->response instanceof InteractionResponse:
                return new Redirect($this->response);
            case $this->response instanceof FormInteractionResponse:
                return new FormPost($this->response);
            case $this->response instanceof FailureResponse:
            default:
                return new Failure($this->order, $this->response, $this->processType);
        }
    }

    /**
     * @param string $orderState
     *
     * @return bool
     * @since 2.1.0
     */
    private function isCancelResponse($orderState)
    {
        return $orderState === Cancel::CANCEL_PAYMENT_STATE;
    }
}
