<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Models;

use Wirecard\PaymentSdk\Response\Response;
use WirecardEE\Prestashop\Helper\TranslationHelper;

/**
 * Basic Transaction class
 *
 * Class Transaction
 * @TODO: Abstract current model to entity and collection wrapper
 * @since 1.0.0
 */
class Transaction extends \ObjectModel
{
    use TranslationHelper;

    /** @var string  */
    const TRANSACTION_STATE_OPEN = 'open';

    /** @var string */
    const TRANSLATION_FILE = "transaction";

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
     * Create transaction in wirecard_payment_gateway_tx
     *
     * @param int $idOrder
     * @param int $idCart
     * @param float $amount
     * @param string $currency
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
        $currency,
        $response,
        $transactionState,
        $orderNumber = null
    ) {
        $db = \Db::getInstance();
        $parentTransactionId = '';

        if ((new Transaction)->get($response->getParentTransactionId())) {
            $parentTransactionId = $response->getParentTransactionId();
        }

        $db->insert('wirecard_payment_gateway_tx', array(
            'transaction_id' => pSQL($response->getTransactionId()),
            'parent_transaction_id' => pSQL($parentTransactionId),
            'order_id' => $idOrder === null ? 'NULL' : (int)$idOrder,
            'ordernumber' => $orderNumber === null ? 'NULL' : pSQL($orderNumber),
            'cart_id' => (int)$idCart,
            'paymentmethod' => pSQL($response->getPaymentMethod()),
            'transaction_state' => pSQL($transactionState),
            'transaction_type' => pSQL($response->getTransactionType()),
            'amount' => (float)$amount,
            'currency' => pSQL($currency),
            'response' => pSQL(json_encode($response->getData())),
            'created' => 'NOW()'
        ));

        if ($db->getNumberError() > 0) {
            throw new \PrestaShopDatabaseException($db->getMsgError());
        }

        return $db->Insert_ID();
    }

    /**
     * Get single transaction per transaction id
     *
     * @param string $transactionId
     * @return mixed
     * @since 1.0.0
     */
    public function get($transactionId)
    {
        $query = new \DbQuery();
        $query->from('wirecard_payment_gateway_tx')->where('transaction_id = ' . (int)$transactionId);

        return \Db::getInstance()->getRow($query);
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
}
