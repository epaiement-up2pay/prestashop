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

namespace WirecardEE\Prestashop\Classes\Response;

use Wirecard\PaymentSdk\Response\SuccessResponse;
use WirecardEE\Prestashop\Helper\DBTransactionManager;
use WirecardEE\Prestashop\Helper\Logger;
use WirecardEE\Prestashop\Helper\OrderManager;
use WirecardEE\Prestashop\Helper\Service\OrderService;
use WirecardEE\Prestashop\Helper\TranslationHelper;
use WirecardEE\Prestashop\Models\Transaction;

/**
 * Class Success
 * @package WirecardEE\Prestashop\Classes\Response
 * @since 2.1.0
 */
abstract class Success implements ProcessablePaymentResponse
{
    use TranslationHelper;

    /** @var string */
    const TRANSLATION_FILE = 'success';

    /** @var \Order */
    protected $order;

    /** @var SuccessResponse */
    protected $response;

    /** @var OrderService */
    protected $orderService;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * SuccessResponseProcessing constructor.
     *
     * @param \Order $order
     * @param SuccessResponse $response
     * @since 2.1.0
     */
    public function __construct($order, $response)
    {
        $this->order = $order;
        $this->response = $response;

        $this->orderService = new OrderService($order);
        $this->logger = new Logger();
    }

    /**
     * @since 2.1.0
     */
    public function process()
    {
        $this->beforeProcess();
        $dbManager = new DBTransactionManager();
        //We do this outside of the try block so that if locking fails, we don't attempt to release it
        $dbManager->acquireLock($this->response->getTransactionId(), 30);
        try {
            $amount = $this->response->getRequestedAmount();
            $orderManager = new OrderManager();
            $transactionState = $orderManager->getTransactionState($this->response);

            Transaction::create(
                $this->order->id,
                $this->order->id_cart,
                $amount,
                $this->response,
                $transactionState,
                $this->order->reference
            );
        } finally {
            $dbManager->releaseLock($this->response->getTransactionId());
        }
        $this->afterProcess();
    }

    /**
     * @since 2.10.0
     */
    protected function beforeProcess()
    {
    }

    /**
     * @since 2.10.0
     */
    protected function afterProcess()
    {
    }
}
