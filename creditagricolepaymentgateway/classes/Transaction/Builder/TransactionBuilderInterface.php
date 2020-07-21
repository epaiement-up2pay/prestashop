<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Transaction\Builder;

use Wirecard\PaymentSdk\Transaction\Transaction;

/**
 * Interface TransactionBuilderInterface
 * @package WirecardEE\Prestashop\Classes\Transaction\Builder
 * @since 2.5.0
 */
interface TransactionBuilderInterface
{
    /**
     * @return Transaction
     * @since 2.5.0
     */
    public function build();
}
