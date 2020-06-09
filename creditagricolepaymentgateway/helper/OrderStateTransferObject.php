<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Helper;

use InvalidArgumentException;
use Wirecard\ExtensionOrderStateModule\Domain\Contract\InputDataTransferObject;
use WirecardEE\Prestashop\Classes\Service\OrderAmountCalculatorService;

/**
 * Class OrderStateTransferObject
 * @package WirecardEE\Prestashop\Helper
 * @since 2.10.0
 */
class OrderStateTransferObject implements InputDataTransferObject
{
    const FIELD_PROCESS_TYPE = "process-type";
    const FIELD_TRANSACTION_TYPE = "transaction-type";
    const FIELD_TRANSACTION_STATE = "transaction-state";
    const FIELD_CURRENT_ORDER_STATE = "current-order-state";
    const FIELD_REQUESTED_AMOUNT = "requested-amount";
    const FIELD_ORDER_TOTAL_AMOUNT = "order-total-amount";
    const FIELD_ORDER_REFUNDED_AMOUNT = "order-refunded-amount";
    const FIELD_ORDER_CAPTURED_AMOUNT = "order-captured-amount";

    /**
     * @var string
     */
    private $processType = "";

    /**
     * @var string
     */
    private $transactionType = "";

    /**
     * @var string
     */
    private $transactionState = "";

    /**
     * @var int
     */
    private $currentOrderState = 0;

    /**
     * @var float
     */
    private $requestedAmount;

    /**
     * @var OrderAmountCalculatorService
     */
    private $orderAmountCalculator;


    /**
     * OrderStateTransferObject constructor.
     * @param $currentOrderState
     * @param $processType
     * @param array $transactionResponse
     * @param OrderAmountCalculatorService $orderAmountCalculator
     */
    public function __construct(
        $currentOrderState,
        $processType,
        array $transactionResponse,
        OrderAmountCalculatorService $orderAmountCalculator
    ) {
        $this->validate($processType, $currentOrderState, $transactionResponse);
        $this->currentOrderState = $currentOrderState;
        $this->processType = $processType;
        $this->transactionState = $transactionResponse[self::FIELD_TRANSACTION_STATE];
        $this->transactionType = $transactionResponse[self::FIELD_TRANSACTION_TYPE];
        $this->requestedAmount = (float)$transactionResponse[self::FIELD_REQUESTED_AMOUNT];
        $this->orderAmountCalculator = $orderAmountCalculator;
    }

    /**
     * @return string
     */
    public function getProcessType()
    {
        return $this->processType;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getTransactionState()
    {
        return $this->transactionState;
    }

    /**
     * @return int
     */
    public function getCurrentOrderState()
    {
        return $this->currentOrderState;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::FIELD_CURRENT_ORDER_STATE => $this->getCurrentOrderState(),
            self::FIELD_TRANSACTION_TYPE => $this->getTransactionType(),
            self::FIELD_TRANSACTION_STATE => $this->getTransactionState(),
            self::FIELD_PROCESS_TYPE => $this->getProcessType(),
            self::FIELD_REQUESTED_AMOUNT => $this->getTransactionRequestedAmount(),
            self::FIELD_ORDER_TOTAL_AMOUNT => $this->getOrderTotalAmount(),
            self::FIELD_ORDER_CAPTURED_AMOUNT => $this->getOrderCapturedAmount(),
            self::FIELD_ORDER_REFUNDED_AMOUNT => $this->getOrderRefundedAmount(),
        ];
    }

    /**
     * @param $processType
     * @param $currentOrderState
     * @param $response
     */
    private function validate($processType, $currentOrderState, $response)
    {
        $response[self::FIELD_PROCESS_TYPE] = $processType;
        $response[self::FIELD_CURRENT_ORDER_STATE] = $currentOrderState;
        $validationSpecs = $this->getRequiredFields();

        $previousException = null;
        foreach ($validationSpecs as $fieldName) {
            if (!isset($response[$fieldName])) {
                $previousException = new InvalidArgumentException(
                    "Required field $fieldName is not set",
                    0,
                    $previousException
                );
            }
        }
        if ($previousException) {
            throw $previousException;
        }
    }

    /**
     * @return float
     */
    public function getTransactionRequestedAmount()
    {
        return $this->requestedAmount;
    }

    /**
     * @return float
     */
    public function getOrderTotalAmount()
    {
        return $this->orderAmountCalculator->getOrderTotalAmount();
    }

    /**
     * @return float
     */
    public function getOrderRefundedAmount()
    {
        return $this->orderAmountCalculator->getOrderRefundedAmount();
    }

    /**
     * @return float
     */
    public function getOrderCapturedAmount()
    {
        return $this->orderAmountCalculator->getOrderCapturedAmount();
    }

    /**
     * @return array
     */
    private function getRequiredFields()
    {
        $validationSpecs = [
            self::FIELD_TRANSACTION_TYPE,
            self::FIELD_TRANSACTION_STATE,
            self::FIELD_PROCESS_TYPE,
            self::FIELD_CURRENT_ORDER_STATE,
            self::FIELD_REQUESTED_AMOUNT,
        ];
        return $validationSpecs;
    }
}
