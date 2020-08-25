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

namespace WirecardEE\Prestashop\Classes\Finder;

use \DbQuery;
use \WirecardEE\Prestashop\Models\Transaction;

/**
 * Class TransactionFinder
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Finder
 */
class TransactionFinder extends DbFinder
{
    /**
     * @return string
     * @since 2.10.0
     */
    protected function getTableName()
    {
        return "wirecard_payment_gateway_tx";
    }

    /**
     * @param $orderId
     * @return Transaction|null
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    public function getCurrentTransactionByOrderId($orderId)
    {
        $transaction = null;
        $queryBuilder = $this->getQueryBuilder();
        $transactionTable = _DB_PREFIX_ . 'wirecard_payment_gateway_tx';
        $query = $queryBuilder->from('wirecard_payment_gateway_tx')
            ->select('`' . $transactionTable . '`.`tx_id`')
            ->leftJoin('orders', 'o', '`' . $transactionTable . '`.`order_id` = o.`id_order`')
            ->leftJoin(
                'order_history',
                'order_history',
                '`' . $transactionTable . '`.`order_id` = order_history.`id_order`'
            )
            ->where(
                '`' . $transactionTable . '`.`order_id` = ' . pSQL($orderId) .
                " AND order_history.`id_order_state` = o.`current_state`"
            );
        if ($result = $this->getDb()->getRow($query)) {
            $transaction = $this->getTransactionByTxId($result['tx_id']);
        }

        return $transaction;
    }

    /**
     * @param int $txId
     * @return Transaction
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    public function getTransactionByTxId($txId)
    {
        return new Transaction((int) $txId);
    }

    /**
     * @param $transactionId
     * @return Transaction|null
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    public function getTransactionById($transactionId)
    {
        $query = $this->getTransactionBuilder()->where('transaction_id = "' . pSQL($transactionId) . '"');
        if ($row = $this->getDb()->getRow($query)) {
            return $this->getTransactionByTxId($row['tx_id']);
        }
        return null;
    }

    /**
     * Loads all transactions of the order
     *
     * @param int $orderId
     * @param null|string $forParentTransactionId
     * @return array|Transaction[]
     * @since 2.10.0
     */
    public function getTransactionListByOrder($orderId, $forParentTransactionId = null)
    {
        $query = 'order_id = "' . pSQL($orderId) . '"';
        if (!is_null($forParentTransactionId)) {
            $query .= ' AND parent_transaction_id = "' . pSQL($forParentTransactionId) . '"';
        }
        $queryBuilder = $this->getTransactionBuilder()->where($query);
        return $this->fetchTransactionByQuery($queryBuilder);
    }

    /**
     * @return \DbQuery
     * @since 2.10.0
     */
    private function getTransactionBuilder()
    {
        return $this->getQueryBuilder()->from($this->getTableName());
    }

    /**
     * @param DbQuery $query
     * @return array|Transaction[]
     * @since 2.10.0
     */
    private function fetchTransactionByQuery(DbQuery $query)
    {
        $children = [];
        $rows = $this->getDb()->executeS($query);
        foreach ($rows as $row) {
            $children[] = $this->getTransactionByTxId(($row['tx_id']));
        }
        return $children;
    }
}
