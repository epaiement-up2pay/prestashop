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

use WirecardEE\Prestashop\Classes\Service\TransactionPossibleOperationService;
use WirecardEE\Prestashop\Classes\Service\TransactionPostProcessingService;
use WirecardEE\Prestashop\Helper\NumericHelper;
use WirecardEE\Prestashop\Helper\PaymentProvider;
use WirecardEE\Prestashop\Helper\Service\ContextService;
use WirecardEE\Prestashop\Helper\TranslationHelper;
use WirecardEE\Prestashop\Helper\TxTranslationHelper;
use WirecardEE\Prestashop\Models\Transaction;

/**
 * Class WirecardTransactions
 *
 * @property CreditAgricolePaymentGateway module
 * @property Transaction $object
 * @since 1.0.0
 */
class WirecardTransactionsController extends ModuleAdminController
{
    use TranslationHelper;
    use NumericHelper;

    /** @var string */
    const TRANSLATION_FILE = "wirecardtransactions";

    /** @var string */
    const BUTTON_RESET = "submitResetwirecard_payment_gateway_tx";

    /** @var ContextService */
    protected $context_service;

    /** @var TxTranslationHelper */
    protected $tx_translation_helper;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'wirecard_payment_gateway_tx';
        $this->className = Transaction::class;
        $this->lang = false;
        $this->addRowAction('view');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;
        $this->context = Context::getContext();
        $this->context_service = new ContextService($this->context);
        $this->identifier = 'tx_id';

        $this->module = Module::getInstanceByName('creditagricolepaymentgateway');

        $this->_defaultOrderBy = 'tx_id';
        $this->_defaultOrderWay = 'DESC';
        $this->_use_found_rows = true;

        $this->fields_list = (new Transaction())->getFieldList();

        if (Tools::isSubmit(self::BUTTON_RESET)) {
            $this->processResetFilters();
        }
        $this->processFilter();

        parent::__construct();
        $this->tpl_folder = 'backend/';
        $this->tx_translation_helper =  new TxTranslationHelper();
    }

    /**
     * Get the current objects' list form the database and modify it.
     *
     * @param int $id_lang Language used for display
     * @param string|null $order_by ORDER BY clause
     * @param string|null $order_way Order way (ASC, DESC)
     * @param int $start Offset in LIMIT clause
     * @param int|null $limit Row count in LIMIT clause
     * @param int|bool $id_lang_shop
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList(
            $id_lang,
            $order_by,
            $order_way,
            $start,
            $limit,
            $id_lang_shop
        );

        foreach ($this->_list as $index => $transaction) {
            $payment = PaymentProvider::getPayment($transaction['paymentmethod']);
            $this->_list[$index]['transaction_type'] =
                $this->tx_translation_helper->translateTxType($transaction['transaction_type']);
            $this->_list[$index]['transaction_state'] =
                $this->tx_translation_helper->translateTxState($transaction['transaction_state']);
            $this->_list[$index]['paymentmethod'] = $payment->displayName();
        }
    }

    /**
     * Render detail transaction view
     *
     * @return mixed
     * @throws Exception
     * @since 1.0.0
     * @since 2.4.0 Major refactoring and simplification
     */
    public function renderView()
    {
        $this->validateTransaction($this->object);
        $possibleOperationService = new TransactionPossibleOperationService($this->object);
        $paymentModel = PaymentProvider::getPayment($this->object->getPaymentMethod());
        $transactionModel = new Transaction($this->object->tx_id);

        // These variables are available in the Smarty context
        $this->tpl_view_vars = [
            'current_index'       => self::$currentIndex,
            'back_link'           => (new Link())->getAdminLink('WirecardTransactions', true),
            'payment_method'      => $paymentModel->getName(),
            'possible_operations' => $possibleOperationService->getPossibleOperationList(),
            'transaction'         => $this->object->toViewArray(),
            'remaining_delta_amount' => $transactionModel->getRemainingAmount(),
            'precision'           => $this->getPrecision(),
            'step'                => $this->calculateNumericInputStep(),
            'regex'               => '/^[+]?(?=.?\d)\d*(\.\d{0,' . $this->getPrecision() . '})?$/',
        ];

        return parent::renderView();
    }

    /**
     * Process follow-up actions such as refund/cancel/etc
     *
     * @throws Exception
     * @since 1.0.0
     * @since 2.4.0 Major refactoring
     * @return bool|ObjectModel
     */
    public function postProcess()
    {
        $operation = \Tools::getValue('operation');
        $transactionId = \Tools::getValue('transaction');

        // This prevents the function from running on the list page
        if (!$operation || !$transactionId) {
            return;
        }

        $parentTransaction = new Transaction($transactionId);
        $delta_amount = Tools::getValue('partial-delta-amount', $parentTransaction->getAmount());

        $transactionPostProcessingService = new TransactionPostProcessingService($operation, $transactionId);
        $transactionPostProcessingService->process((float)$delta_amount);
        if (!empty($transactionPostProcessingService->getErrors())) {
            $this->errors[] = implode("<br />", $transactionPostProcessingService->getErrors());
        }

        return parent::postProcess();
    }

    /**
     * Checks that the transaction data is a valid object from the PrestaShop
     * database and adds an error if this is not the case.
     *
     * @param object $data
     * @throws PrestaShopException
     * @since 2.4.0
     */
    private function validateTransaction($data)
    {
        if (!Validate::isLoadedObject($data)) {
            $this->errors[] = \Tools::displayError(
                $this->getTranslatedString('error_no_transaction')
            );
        }
    }
}
