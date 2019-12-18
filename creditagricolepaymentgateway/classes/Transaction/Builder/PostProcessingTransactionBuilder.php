<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Transaction\Builder;

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Transaction\Transaction;
use WirecardEE\Prestashop\Classes\Transaction\Builder\Entity\EntityBuilderFactory;
use WirecardEE\Prestashop\Models\Payment;
use WirecardEE\Prestashop\Models\Transaction as TransactionModel;

/**
 * Class PostProcessingTransactionBuilder
 * @package WirecardEE\Prestashop\Classes\Transaction\Builder
 * @since 2.5.0
 */
class PostProcessingTransactionBuilder implements TransactionBuilderInterface
{
    /**
     * @var Payment
     */
    private $paymentMethod;

    /**
     * @var TransactionModel
     */
    private $transactionModel;

    /**
     * @var string
     */
    private $operation;

    /**
     * PostProcessingTransactionBuilder constructor.
     * @param Payment $paymentMethod
     * @param TransactionModel $transaction
     * @since 2.5.0
     */
    public function __construct(Payment $paymentMethod, TransactionModel $transaction)
    {
        $this->paymentMethod = $paymentMethod;
        $this->transactionModel = $transaction;
    }

    /**
     * Set the operation of the payment, needed for payment methods that use SEPA Credit
     *
     * @param string $operation
     * @return $this
     * @since 2.5.0
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Builds the transaction
     *
     * @throws \Exception
     * @return Transaction
     * @since 2.5.0
     */
    public function build()
    {
        /** @var Transaction $transaction */
        $transaction = $this->paymentMethod->createTransactionInstance($this->operation);
        $transaction = $this->addPostProcessingMandatoryData($transaction);
        $transaction = $this->addPaymentMethodPostProcessingMandatoryData($transaction);

        return $transaction;
    }

    /**
     * Adds the generic post processing mandatory data(Amount, ParentTransactionId)
     *
     * @param Transaction $transaction
     * @return Transaction
     * @since 2.5.0
     */
    private function addPostProcessingMandatoryData($transaction)
    {
        $transaction->setAmount(
            new Amount(
                (float) $this->transactionModel->getAmount(),
                $this->transactionModel->getCurrency()
            )
        );

        $transaction->setParentTransactionId(
            $this->transactionModel->getTransactionId()
        );

        return $transaction;
    }

    /**
     * Adds the payment method specific mandatory data to transaction
     *
     * @param Transaction $transaction
     * @throws \Exception
     * @return Transaction
     * @since 2.5.0
     */
    private function addPaymentMethodPostProcessingMandatoryData($transaction)
    {
        foreach ($this->paymentMethod->getPostProcessingMandatoryEntities() as $entity) {
            $entityBuilder = (new EntityBuilderFactory($this->transactionModel))->create($entity);
            $transaction = $entityBuilder->build($transaction);
        }

        return $transaction;
    }
}
