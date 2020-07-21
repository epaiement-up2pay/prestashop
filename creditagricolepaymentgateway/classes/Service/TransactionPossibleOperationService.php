<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Service;

use \Exception;
use \Tools;
use Wirecard\PaymentSdk\BackendService;
use Wirecard\PaymentSdk\Transaction\MasterpassTransaction;
use Wirecard\PaymentSdk\Transaction\Operation;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use WirecardEE\Prestashop\Classes\Config\PaymentConfigurationFactory;
use WirecardEE\Prestashop\Helper\Logger;
use WirecardEE\Prestashop\Helper\Logger as WirecardLogger;
use WirecardEE\Prestashop\Helper\NumericHelper;
use WirecardEE\Prestashop\Helper\PaymentProvider;
use WirecardEE\Prestashop\Helper\Service\ShopConfigurationService;
use WirecardEE\Prestashop\Helper\TranslationHelper;
use WirecardEE\Prestashop\Models\PaymentSepaCreditTransfer;
use WirecardEE\Prestashop\Models\Transaction;
use Wirecard\PaymentSdk\Transaction\Transaction as TransactionTypes;

/**
 * Class TransactionPossibleOperationService
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Service
 */
class TransactionPossibleOperationService implements ServiceInterface
{
    use TranslationHelper;

    use NumericHelper;

    /** @var string */
    const TRANSLATION_FILE = "transactionpossibleoperationservice";

    /** @var Transaction */
    private $transaction;

    /**
     * TransactionPossibleOperationService constructor.
     * @param Transaction $transaction
     * @throws Exception
     * @since 2.5.0
     */
    public function __construct($transaction)
    {
        if (!$transaction instanceof Transaction) {
            throw new Exception("transaction isn't instance of Transaction");
        }
        $this->transaction = $transaction;
    }

    /**
     * @return BackendService
     * @since 2.5.0
     */
    private function getBackendService()
    {
        return new BackendService($this->getPaymentConfig(), new WirecardLogger());
    }

    /**
     * @return ShopConfigurationService
     * @since 2.5.0
     */
    private function getShopConfigurationService()
    {
        return new ShopConfigurationService($this->transaction->getPaymentMethod());
    }

    /**
     * @return \Wirecard\PaymentSdk\Config\Config
     * @since 2.5.0
     */
    private function getPaymentConfig()
    {
        return (new PaymentConfigurationFactory($this->getShopConfigurationService()))->createConfig();
    }

    /**
     * @param bool $returnTemplateFormat
     * @return array|bool
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @since 2.5.0
     */
    public function getPossibleOperationList($returnTemplateFormat = true)
    {
        $possibleOperations = [];
        $paymentModel = PaymentProvider::getPayment($this->transaction->getPaymentMethod());
        try {
            $transaction = $paymentModel->createTransactionInstance();
            $transaction->setParentTransactionId($this->transaction->getTransactionId());
            $result = $this->getBackendService()->retrieveBackendOperations($transaction, true);
            if (is_array($result)) {
                $possibleOperations = $result;
            }
        } catch (Exception $exception) {
            (new Logger())->error($exception->getMessage());
        }

        $possibleOperations = $this->disallowOperationsIfPartialOperationsDone($possibleOperations);
        $isParent = empty($this->transaction->getParentTransactionId());
        $possibleOperations = $this->filterBasedOnType($possibleOperations, $isParent);

        // We no longer support Masterpass
        if ($returnTemplateFormat && $this->transaction->getPaymentMethod() !== MasterpassTransaction::NAME) {
            $possibleOperations = $this->getOperationsForTemplate($possibleOperations);
        }

        return $possibleOperations;
    }

    /**
     * We cannot cancel after making partial refunds / captures.
     *
     * We cannot capture if amount left is zero.
     *
     * @param $possibleOperations
     * @return array
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    private function disallowOperationsIfPartialOperationsDone($possibleOperations)
    {
        if (is_array($possibleOperations)) {
            if ($this->transaction->getProcessedAmount() > 0) {
                unset($possibleOperations[Operation::CANCEL]);
            }
            if ($this->transaction->isZeroRemaining()) {
                unset($possibleOperations[Operation::PAY]);
            }
        }
        return $possibleOperations;
    }

    /**
     * Formats the post-processing operations for use in the template.
     *
     * @param array $possibleOperations
     * @return array
     * @since 2.5.0
     */
    private function getOperationsForTemplate(array $possibleOperations)
    {
        $sepaCreditConfig = new ShopConfigurationService(PaymentSepaCreditTransfer::TYPE);
        $operations = [];
        $translations = [
            //@TODO Add constant to paymentSDK
            'capture' => $this->getTranslatedString('text_capture_transaction'),
            Operation::CANCEL => $this->getTranslatedString('text_cancel_transaction'),
            Operation::REFUND => $this->getTranslatedString('text_refund_transaction'),
        ];

        if ($possibleOperations === false) {
            return $operations;
        }

        foreach ($possibleOperations as $operation => $key) {
            if (!$sepaCreditConfig->getField('enabled') && $operation === Operation::CREDIT) {
                continue;
            }
            $translatable_key = Tools::strtolower($key);
            $operations[] = [
                "action" => $operation,
                "name" => $translations[$translatable_key]
            ];
        }

        return $operations;
    }

    /**
     * @param string $operation
     * @return bool
     * @throws Exception
     * @since 2.5.0
     */
    public function isOperationPossible($operation)
    {
        $operations = $this->getPossibleOperationList(false);
        return in_array($operation, array_keys($operations), true);
    }

    /**
     * @param $possibleOperations string[]
     * @param $isParent
     * @return string[]
     * @since 2.10.0
     */
    private function filterBasedOnType(array $possibleOperations, $isParent)
    {
        $type = $this->transaction->getTransactionType();
        $paymentMethod = $this->transaction->getPaymentMethod();
        $noRefundTypes = [];
        $whitelistedCancelPaymentMethods = [
            PayPalTransaction::NAME,
        ];

        $noCancelTypes = [
            TransactionTypes::TYPE_CAPTURE_AUTHORIZATION,
        ];

        if ($isParent) {
            $noCancelTypes = [
                TransactionTypes::TYPE_PURCHASE,
            ];
            $noRefundTypes = [
                TransactionTypes::TYPE_CAPTURE_AUTHORIZATION
            ];
        }

        if (in_array($type, $noCancelTypes, true)) {
            if (!in_array($paymentMethod, $whitelistedCancelPaymentMethods)) {
                unset($possibleOperations[Operation::CANCEL]);
            }
        }
        if (in_array($type, $noRefundTypes, true)) {
            unset($possibleOperations[Operation::REFUND]);
        }

        return $possibleOperations;
    }
}
