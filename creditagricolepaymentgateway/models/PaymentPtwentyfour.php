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

namespace WirecardEE\Prestashop\Models;

use Wirecard\PaymentSdk\Transaction\PtwentyfourTransaction;
use WirecardEE\Prestashop\Helper\AdditionalInformationBuilder;

/**
 * Class PaymentPtwentyfour
 *
 * @extends Payment
 *
 * @since 1.0.0
 */
class PaymentPtwentyfour extends Payment
{
    /**
     * @var string
     * @since 2.1.0
     */
    const TYPE = PtwentyfourTransaction::NAME;

    /**
     * @var string
     * @since 2.1.0
     */
    const TRANSLATION_FILE = "paymentptwentyfour";

    /**
     * PaymentPtwentyfour constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->type = self::TYPE;
        $this->name = $this->getTranslatedString('ptwentyfour');
        $this->formFields = $this->createFormFields();
    }

    /**
     * Create form fields for Pwentyfour
     *
     * @return array|null
     * @since 1.0.0
     */
    public function createFormFields()
    {
        return array(
            'tab' => 'P24',
            'fields' => array(
                array(
                    'name' => 'enabled',
                    'label' => $this->getTranslatedString('text_enable'),
                    'type' => 'onoff',
                    'doc' => $this->getTranslatedString('enable_heading_title_ptwentyfour'),
                    'default' => 0,
                ),
                array(
                    'name' => 'title',
                    'label' => $this->getTranslatedString('config_title'),
                    'type' => 'text',
                    'default' => $this->getTranslatedString('heading_title_ptwentyfour'),
                    'required' => true,
                ),
                array(
                    'name' => 'merchant_account_id',
                    'label'   => $this->getTranslatedString('config_merchant_account_id'),
                    'type'    => 'text',
                    'default' => $this->credentialsConfig->getMerchantAccountId(),
                    'required' => true,
                ),
                array(
                    'name' => 'secret',
                    'label'   => $this->getTranslatedString('config_merchant_secret'),
                    'type'    => 'text',
                    'default' => $this->credentialsConfig->getSecret(),
                    'required' => true,
                ),
                array(
                    'name' => 'base_url',
                    'label'       => $this->getTranslatedString('config_base_url'),
                    'type'        => 'text',
                    'doc' => $this->getTranslatedString('config_base_url_desc'),
                    'default'     => $this->credentialsConfig->getBaseUrl(),
                    'required' => true,
                ),
                array(
                    'name' => 'http_user',
                    'label'   => $this->getTranslatedString('config_http_user'),
                    'type'    => 'text',
                    'default' => $this->credentialsConfig->getHttpUser(),
                    'required' => true,
                ),
                array(
                    'name' => 'http_pass',
                    'label'   => $this->getTranslatedString('config_http_password'),
                    'type'    => 'text',
                    'default' => $this->credentialsConfig->getHttpPassword(),
                    'required' => true,
                ),
                array(
                    'name' => 'payment_action',
                    'type'    => 'hidden',
                    'default' => 'pay',
                ),
                array(
                    'name' => 'descriptor',
                    'label'   => $this->getTranslatedString('config_descriptor'),
                    'type'    => 'onoff',
                    'default' => 0,
                ),
                array(
                    'name' => 'send_additional',
                    'label'   => $this->getTranslatedString('config_additional_info'),
                    'type'    => 'onoff',
                    'default' => 1,
                ),
                array(
                    'name' => 'test_credentials',
                    'type' => 'linkbutton',
                    'required' => false,
                    'buttonText' => $this->getTranslatedString('test_config'),
                    'id' => 'p24Config',
                    'method' => 'P24',
                    'send' => array(
                        'WIRECARD_PAYMENT_GATEWAY_P24_BASE_URL',
                        'WIRECARD_PAYMENT_GATEWAY_P24_HTTP_USER',
                        'WIRECARD_PAYMENT_GATEWAY_P24_HTTP_PASS'
                    )
                )
            )
        );
    }

    /**
     * Create Ptwentyfour transaction
     *
     * @param \CreditAgricolePaymentGateway $module
     * @param \Cart $cart
     * @param array $values
     * @param int $orderId
     * @return null|PtwentyfourTransaction
     * @since 1.0.0
     */
    public function createTransaction($operation = null)
    {
        $context = \Context::getContext();
        $cart = $context->cart;
        $transaction = $this->createTransactionInstance($operation);

        $additionalInformation = new AdditionalInformationBuilder();
        $transaction->setAccountHolder($additionalInformation->createAccountHolder($cart, 'billing'));

        return $transaction;
    }

    /**
     * Get a clean transaction instance for this payment type.
     *
     * @param string $operation
     * @return PtwentyfourTransaction
     * @since 2.4.0
     */
    public function createTransactionInstance($operation = null)
    {
        return new PtwentyfourTransaction();
    }
}
