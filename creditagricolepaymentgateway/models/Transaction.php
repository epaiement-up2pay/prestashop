<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Models;

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\Response;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use WirecardEE\Prestashop\Helper\DBTransactionManager;
use WirecardEE\Prestashop\Helper\NumericHelper;
use WirecardEE\Prestashop\Helper\OrderManager;
use WirecardEE\Prestashop\Helper\Service\OrderService;
use WirecardEE\Prestashop\Helper\TranslationHelper;
use Wirecard\PaymentSdk\Transaction\Transaction as TransactionTypes;

/**
 * Basic Transaction class
 *
 * Class Transaction
 * @TODO: Abstract current model to entity and collection wrapper
 * @since 1.0.0
 */
class Transaction extends \ObjectModel implements SettleableTransaction
{
    use TranslationHelper;
    use NumericHelper;

    /** @var string  */
    const TRANSACTION_STATE_OPEN = 'open';

    /** @var string */
    const TRANSLATION_FILE = "transaction";

    /** @var array */
    const DEDUCTING_TYPES = [
        TransactionTypes::TYPE_CREDIT,
        TransactionTypes::TYPE_PENDING_CREDIT,
        TransactionTypes::TYPE_REFUND_CAPTURE,
        TransactionTypes::TYPE_REFUND_DEBIT,
        TransactionTypes::TYPE_REFUND_PURCHASE,
        TransactionTypes::TYPE_REFUND_REQUEST,
        TransactionTypes::TYPE_VOID_AUTHORIZATION,
        TransactionTypes::TYPE_VOID_CAPTURE,
        TransactionTypes::TYPE_VOID_CREDIT,
        TransactionTypes::TYPE_VOID_DEBIT,
        TransactionTypes::TYPE_VOID_PURCHASE,
    ];

    /** @var array */
    const CAPTURING_TYPES = [
        TransactionTypes::TYPE_CAPTURE_AUTHORIZATION,
        TransactionTypes::TYPE_PURCHASE,
        TransactionTypes::TYPE_DEBIT,
        TransactionTypes::TYPE_DEPOSIT,
    ];

    public $tx_id;

    public $transaction_id;

    public $parent_transaction_id;

    public $order_id;

    public $cart_id;

    public $ordernumber;

    public $paymentmethod;

    public $transaction_state;

    public $amount;

    public $currency;

    public $response;

    public $transaction_type;

    public $created;

    public $modified;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'wirecard_payment_gateway_tx',
        'primary' => 'tx_id',
        'fields' => array(
            'transaction_id' => array('type' => self::TYPE_STRING),
            'parent_transaction_id' => array('type' => self::TYPE_STRING),
            'order_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'cart_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'ordernumber' => array('type' => self::TYPE_STRING),
            'paymentmethod' => array('type' => self::TYPE_STRING, 'required' => true),
            'transaction_type' => array('type' => self::TYPE_STRING, 'required' => true),
            'transaction_state' => array('type' => self::TYPE_STRING, 'required' => true),
            'amount' => array('type' => self::TYPE_FLOAT, 'required' => true),
            'currency' => array('type' => self::TYPE_STRING, 'required' => true),
            'response' => array('type' => self::TYPE_STRING),
            'created' => array('type' => self::TYPE_DATE, 'required' => true),
            'modified' => array('type' => self::TYPE_DATE),
        ),
    );

    /**
     * @return string
     */
    public function getTxId()
    {
        return $this->tx_id;
    }

    /**
     * @param string $tx_id
     */
    public function setTxId($tx_id)
    {
        $this->tx_id = $tx_id;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * @param string $transaction_id
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * @return string
     */
    public function getParentTransactionId()
    {
        return $this->parent_transaction_id;
    }

    /**
     * @param string $parent_transaction_id
     */
    public function setParentTransactionId($parent_transaction_id)
    {
        $this->parent_transaction_id = $parent_transaction_id;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return int
     */
    public function getCartId()
    {
        return $this->cart_id;
    }

    /**
     * @param int $cart_id
     */
    public function setCartId($cart_id)
    {
        $this->cart_id = $cart_id;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->ordernumber;
    }

    /**
     * @param string $order_number
     */
    public function setOrderNumber($order_number)
    {
        $this->ordernumber = $order_number;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentmethod;
    }

    /**
     * @param string $payment_method
     */
    public function setPaymentMethod($payment_method)
    {
        $this->paymentmethod = $payment_method;
    }

    /**
     * @return string
     */
    public function getTransactionState()
    {
        return $this->transaction_state;
    }

    /**
     * @param string $transaction_state
     */
    public function setTransactionState($transaction_state)
    {
        $this->transaction_state = $transaction_state;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     * @param string $transaction_type
     */
    public function setTransactionType($transaction_type)
    {
        $this->transaction_type = $transaction_type;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return bool
     * @since 2.5.0
     */
    public function isTransactionStateOpen()
    {
        return $this->getTransactionState() == self::TRANSACTION_STATE_OPEN;
    }

    /**
     * Create transaction in wirecard_payment_gateway_tx
     *
     * @param int $idOrder
     * @param int $idCart
     * @param Amount $amount
     * @param string $transactionState
     * @param string $orderNumber
     * @param Response $response
     * @return mixed
     * @since 1.0.0
     */
    public static function create(
        $idOrder,
        $idCart,
        $amount,
        $response,
        $transactionState,
        $orderNumber = null
    ) {
        $db = \Db::getInstance();
        $parentTransactionId = '';

        if ((new Transaction)->hydrateByTransactionId($response->getParentTransactionId())) {
            $parentTransactionId = $response->getParentTransactionId();
        }

        //@TODO: change this once TPWDCEE-5667 is implemented in the SDK
        $paymentMethod = $response->getPaymentMethod();
        if (!$paymentMethod) {
            $data = $response->getData();
            $paymentMethod = $data['payment-method'];
        }

        $tx_id = null;

        $tempTransaction = new Transaction();
        $transactionExists = $tempTransaction->hydrateByTransactionId($response->getTransactionId());
        if ($transactionExists) {
            $tx_id = $tempTransaction->getTxId();
        }

        $data = [
            'transaction_id' => pSQL($response->getTransactionId()),
            'parent_transaction_id' => pSQL($parentTransactionId),
            'order_id' => $idOrder === null ? 'NULL' : (int)$idOrder,
            'ordernumber' => $orderNumber === null ? 'NULL' : pSQL($orderNumber),
            'cart_id' => (int)$idCart,
            'paymentmethod' => pSQL($paymentMethod),
            'transaction_state' => pSQL($transactionState),
            'transaction_type' => pSQL($response->getTransactionType()),
            'amount' => $amount->getValue(),
            'currency' => pSQL($amount->getCurrency()),
            'response' => pSQL(json_encode($response->getData())),
            'modified' => (new \DateTime())->format(DATE_ISO8601),
        ];

        if ($transactionExists && $tx_id) {
            $data['tx_id'] = $tx_id;
            $success = $db->update('wirecard_payment_gateway_tx', $data, "tx_id = $tx_id");
        } else {
            $data['created'] = (new \DateTime())->format(DATE_ISO8601);
            $success = $db->insert('wirecard_payment_gateway_tx', $data);
        }

        if ($db->getNumberError() > 0) {
            throw new \PrestaShopDatabaseException($db->getMsgError());
        }

        if (!$success) {
            throw new \PrestaShopModuleException("An unknown error occured");
        }

        return $db->Insert_ID();
    }

    /**
     * Loads data into the model through the gateway transaction ID.
     *
     * @param $transactionId
     * @return bool
     * @throws \Exception
     * @since 2.5.0
     */
    public function hydrateByTransactionId($transactionId)
    {
        $query = new \DbQuery();
        $query->from('wirecard_payment_gateway_tx')->where('transaction_id = "' . pSQL($transactionId) . '"');

        $data = \Db::getInstance()->getRow($query);

        if (!$data) {
            return false;
        }

        $this->tx_id = (int)$data['tx_id'];

        foreach (self::$definition['fields'] as $fieldName => $fieldSpecification) {
            if (isset($data[$fieldName])) {
                switch ($fieldSpecification['type']) {
                    case self::TYPE_INT:
                        $data[$fieldName] = (int)$data[$fieldName];
                        break;
                    case self::TYPE_FLOAT:
                        $data[$fieldName] = (float)$data[$fieldName];
                        break;
                    case self::TYPE_DATE:
                        $data[$fieldName] = new \DateTime($data[$fieldName]);
                        break;
                    case self::TYPE_STRING:
                        $data[$fieldName] = (string)$data[$fieldName];
                        break;
                }

                $this->$fieldName = $data[$fieldName];
            }
        }
        return true;
    }

    public static function getInitialTransactionForOrder($reference)
    {
        $query = new \DbQuery();
        $query->from('wirecard_payment_gateway_tx')
            ->where('ordernumber = "' . pSQL($reference) . '"')
            ->orderBy('tx_id ASC')
            ->limit(1);

        $rows = \Db::getInstance()->executeS($query);

        if ($rows) {
            return new self($rows[0]['tx_id']);
        }

        return false;
    }

    /**
     * Loads all children of the transaction
     *
     * @return Transaction[]
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    private function getAllChildTransactions()
    {
        $children = [];
        $parent_id = $this->getTransactionId();

        $query = new \DbQuery();
        $query->from('wirecard_payment_gateway_tx')
            ->where('parent_transaction_id = "' . pSQL($parent_id) . '"');

        $rows = \Db::getInstance()->executeS($query);

        foreach ($rows as $row) {
            $children[] = new Transaction($row['tx_id']);
        }

        return $children;
    }

    private function getAllTransitiveChildTransactions()
    {
        $children = [];
        $orderNumber = $this->getOrderNumber();

        $query = new \DbQuery();
        $query->from('wirecard_payment_gateway_tx')
            ->where('ordernumber="'.pSQL($orderNumber).'"')
            ->where('LENGTH(parent_transaction_id) = 36');

        $rows = \Db::getInstance()->executeS($query);

        foreach ($rows as $row) {
            $children[] = new Transaction($row['tx_id']);
        }

        return $children;
    }

    public function getFieldList()
    {
        return array(
            'tx_id' => array(
                'title' => $this->getTranslatedString('panel_transaction'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'transaction_id' => array(
                'title' => $this->getTranslatedString('panel_transcation_id'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'parent_transaction_id' => array(
                'title' => $this->getTranslatedString('panel_parent_transaction_id'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'amount' => array(
                'title' => $this->getTranslatedString('panel_amount'),
                'align' => 'text-right',
                'class' => 'fixed-width-xs',
                'type' => 'price',
            ),
            'currency' => array(
                'title' => $this->getTranslatedString('panel_currency'),
                'class' => 'fixed-width-xs',
                'align' => 'text-right',
            ),
            'ordernumber' => array(
                'title' => $this->getTranslatedString('panel_order_number'),
                'class' => 'fixed-width-lg',
            ),
            'cart_id' => array(
                'title' => $this->getTranslatedString('panel_cart_number'),
                'class' => 'fixed-width-lg',
            ),
            'paymentmethod' => array(
                'title' => $this->getTranslatedString('panel_payment_method'),
                'class' => 'fixed-width-lg',
            ),
            'transaction_type' => array(
                'title' => $this->getTranslatedString('transactionType'),
                'class' => 'fixed-width-xs',
            ),
            'transaction_state' => array(
                'title' => $this->getTranslatedString('transactionState'),
                'class' => 'fixed-width-xs',
            ),

        );
    }

    /**
     * Maps the database columns into an easily digestible array.
     * @return array
     * @since 2.4.0
     */
    public function toViewArray()
    {
        return [
            'tx'             => $this->tx_id,
            'id'             => $this->transaction_id,
            'type'           => $this->transaction_type,
            'status'         => $this->transaction_state,
            'amount'         => $this->amount,
            'currency'       => $this->currency,
            'response'       => json_decode($this->response),
            'payment_method' => $this->paymentmethod,
            'order'          => $this->ordernumber,
            'badge'          => $this->isTransactionStateOpen() ? 'green' : 'red',
        ];
    }

    /**
     * The transaction is a deduction from the parent transaction.
     *
     * @return bool true if th
     */
    private function isDeducting()
    {
        return in_array($this->getTransactionType(), self::DEDUCTING_TYPES);
    }

    /**
     * The transaction is a capture from the parent transaction.
     * @return bool
     */
    private function isCapturing()
    {
        return in_array($this->getTransactionType(), self::CAPTURING_TYPES);
    }

    /**
     * Calculates the sum of all child transactions of this transaction.
     *
     * @return float|int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function getProcessedCaptureAmount()
    {
        $childTransactions = $this->getAllChildTransactions();
        return $this->sumCapturingChildren($childTransactions);
    }

    /**
     * Calculates the sum of all child transactions of this transaction.
     *
     * @return float|int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function getProcessedCaptureAmountTransitive()
    {
        $childTransactions = $this->getAllTransitiveChildTransactions();
        return $this->sumCapturingChildren($childTransactions);
    }

    /**
     * @param $childTransactions Transaction[]
     * @return float|int
     */
    private function sumCapturingChildren($childTransactions)
    {
        $sum = 0;
        foreach ($childTransactions as $child) {
            if ($child->isCapturing()) {
                $sum += $child->getAmount();
            }
        }
        return $sum;
    }

    public function getProcessedAmount()
    {
        return $this->getProcessedRefundAmount() + $this->getProcessedCaptureAmount();
    }

    /**
     * Calculates the sum of all child transactions of this transaction.
     *
     * @return float|int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function getProcessedRefundAmount()
    {
        $childTransactions = $this->getAllChildTransactions();
        return $this->sumDeductingChildren($childTransactions);
    }

    /**
     * Calculates the sum of all child transactions of this transaction.
     *
     * @return float|int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function getProcessedRefundAmountTransitive()
    {
        $childTransactions = $this->getAllTransitiveChildTransactions();
        return $this->sumDeductingChildren($childTransactions);
    }

    /**
     * @param $childTransactions Transaction[]
     * @return int
     */
    private function sumDeductingChildren($childTransactions)
    {
        $sum = 0;
        foreach ($childTransactions as $child) {
            if ($child->isDeducting()) {
                $sum += $child->getAmount();
            }
        }
        return $sum;
    }

    /**
     * Given some partial refunds / captures, return the remaining amount to be paid by the customer.
     *
     * @return float|int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getRemainingAmount()
    {
        $amount = $this->getAmount();
        $processedAmount = $this->getProcessedAmount();
        return $this->difference($amount, $processedAmount);
    }

    /**
     * @return bool
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function markSettledAsClosed()
    {
        if ($this->isSettled()) {
            $transactionManager = new DBTransactionManager();
            $transactionManager->markTransactionClosed($this->getTransactionId());
            return true;
        }
        return false;
    }

    /**
     * @return bool
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function isSettled()
    {
        return $this->isRefundSettled() || $this->isCaptureSettled();
    }

    private function isRefundSettled()
    {
        return $this->equals($this->getProcessedRefundAmount(), $this->getAmount());
    }

    private function isCaptureSettled()
    {
        return $this->equals($this->getProcessedCaptureAmount(), $this->getAmount());
    }

    private function isRefundSettledTransitive()
    {
        return $this->equals($this->getProcessedRefundAmountTransitive(), $this->getAmount());
    }

    public function isCaptureSettledTransitive()
    {
        return $this->equals($this->getProcessedCaptureAmountTransitive(), $this->getAmount());
    }

    private function isOrderStateCancel($orderState)
    {
        return intval($orderState) === intval(_PS_OS_CANCELED_);
    }


    /**
     * @param \Order $order
     * @param SuccessResponse $notification
     * @param OrderManager $orderManager
     * @param OrderService $orderService
     * @return bool
     * @throws \PrestaShopException
     */
    public function updateOrder(
        \Order $order,
        SuccessResponse $notification,
        OrderManager $orderManager,
        OrderService $orderService
    ) {
        $updated = false;

        $orderState = $orderManager->orderStateToPrestaShopOrderState($notification);

        if ($notification->getTransactionType() == TransactionTypes::TYPE_AUTHORIZATION) {
            $order->setCurrentState($orderState);
            $order->save();
            $updated = true;
        }

        if (!$updated && $this->isOrderStateCancel($orderState)) {
            $order->setCurrentState(_PS_OS_CANCELED_);
            $order->save();
            $updated = true;
        }

        $settled = false;
        if (!$updated && $this->isCaptureSettledTransitive()) {
            if ($orderState != _PS_OS_REFUND_) {
                $order->setCurrentState($orderState);
                $order->save();
                $updated = true;
                $settled = true;
            }
        }

        if (!$updated && $this->isRefundSettledTransitive()) {
            $order->setCurrentState(_PS_OS_REFUND_);
            $order->save();
            $updated = true;
        }

        if ($settled) {
            $parentTransaction = Transaction::getInitialTransactionForOrder($order->reference);
            $amount = $parentTransaction->getAmount();
            $orderService->updateOrderPayment(
                $notification->getTransactionId(),
                $amount
            );
        }

        return $updated;
    }
}
