<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Hook;

use \Configuration;
use \Exception;
use \PrestaShopDatabaseException;
use \PrestaShopException;
use Wirecard\PaymentSdk\Transaction\Operation;
use WirecardEE\Prestashop\Classes\Constants\ConfigConstants;
use WirecardEE\Prestashop\Classes\Finder\TransactionFinder;
use WirecardEE\Prestashop\Classes\Service\TransactionPossibleOperationService;
use WirecardEE\Prestashop\Classes\Service\TransactionPostProcessingService;
use WirecardEE\Prestashop\Models\Transaction;

/**
 * class BeforeOrderStatusUpdateHandler
 * @since 2.5.0
 * @package WirecardEE\Prestashop\Classes\Hook
 */
class BeforeOrderStatusUpdateHandler implements CommandHandlerInterface
{
    /** @var int */
    private $changeToStatusId;
    /** @var OrderStatusUpdateCommand */
    private $command;

    /**
     * OrderStatusPostUpdateHandler constructor.
     * @param int $changeToStatusId
     * @param OrderStatusUpdateCommand $command
     * @since 2.5.0
     */
    public function __construct($changeToStatusId, OrderStatusUpdateCommand $command)
    {
        $this->command = $command;
        $this->changeToStatusId = (int)$changeToStatusId;
    }

    /**
     * @return bool
     * @since 2.5.0
     */
    private function isOrderStateMatching()
    {
        return $this->command->getOrderState()->id === $this->changeToStatusId;
    }

    /**
     * @return bool
     * @since 2.5.0
     */
    private function isAutoCaptureEnabled()
    {
        return intval(Configuration::get(ConfigConstants::SETTING_GENERAL_AUTOMATIC_CAPTURE_ENABLED));
    }

    /**
     * @return bool
     * @throws Exception
     * @since 2.5.0
     */
    private function validate()
    {
        return $this->isOrderStateMatching() && $this->isAutoCaptureEnabled();
    }

    /**
     * @param Transaction $transaction
     * @return bool
     * @throws Exception
     * @since 2.5.0
     */
    protected function isTransactionPayable($transaction)
    {
        if (is_null($transaction) || !$transaction->isTransactionStateOpen()) {
            return false;
        }

        return (new TransactionPossibleOperationService($transaction))->isOperationPossible(Operation::PAY);
    }

    /**
     * @param string $operation
     * @param Transaction $transaction
     * @since 2.5.0
     */
    protected function handlePostProcessing($operation, $transaction)
    {
        $postProcessingService = new TransactionPostProcessingService($operation, $transaction->getTxId());
        $parentTransaction = Transaction::getInitialTransactionForOrder($transaction->getOrderNumber());
        $postProcessingService->process((float)$parentTransaction->getAmount());
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws Exception
     * @since 2.5.0
     */
    protected function onChangeToShippingStatus()
    {
        if (!$this->validate()) {
            return;
        }

        $transaction = (new TransactionFinder())->getCurrentTransactionByOrderId($this->command->getOrderId());

        if ($this->isTransactionPayable($transaction)) {
            $this->handlePostProcessing(Operation::PAY, $transaction);
        }
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @since 2.5.0
     */
    public function handle()
    {
        // Must be moved to a separate class in case of expansion of logic
        $this->onChangeToShippingStatus();
    }
}
