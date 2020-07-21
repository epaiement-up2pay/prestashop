<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/epaiement-up2pay/prestashop/blob/master/LICENSE
 */

namespace WirecardEE\Prestashop\Classes\Config;

use Wirecard\PaymentSdk\Config\Config;
use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;
use Wirecard\PaymentSdk\Transaction\SepaDirectDebitTransaction;
use WirecardEE\Prestashop\Helper\Service\ShopConfigurationService;
use WirecardEE\Prestashop\Models\PaymentIdeal;
use WirecardEE\Prestashop\Models\PaymentSepaCreditTransfer;
use WirecardEE\Prestashop\Models\PaymentSepaDirectDebit;
use WirecardEE\Prestashop\Models\PaymentSofort;

/**
 * Class PaymentConfigurationFactory
 *
 * @package WirecardEE\Prestashop\Classes\Config
 * @since 2.1.0
 */
class PaymentConfigurationFactory
{
    const SEPA_CREDIT_TYPES = [
        PaymentIdeal::TYPE,
        PaymentSepaDirectDebit::TYPE,
        PaymentSofort::TYPE
    ];

    /**
     * @var ShopConfigurationService
     * @since 2.1.0
     */
    protected $configService;

    /**
     * @var boolean
     * @since 2.4.0
     */
    protected $requiresSepaCredit;

    /**
     * PaymentConfigurationFactory constructor.
     *
     * @param ShopConfigurationService $configService
     * @since 2.1.0
     */
    public function __construct(ShopConfigurationService $configService)
    {
        $this->configService = $configService;
        $this->requiresSepaCredit = in_array(
            $this->configService->getType(),
            self::SEPA_CREDIT_TYPES
        );
    }

    /**
     * Builds up a paymentSdk config containing the specific payment method config
     *
     * @return Config
     * @since 2.1.0
     */
    public function createConfig()
    {
        $factoryType = $this->resolveFactoryType();

        /** @var ConfigurationFactoryInterface $configFactory */
        $configFactory = new $factoryType($this->configService);

        $sdkConfiguration = new Config(
            $this->configService->getField('base_url'),
            $this->configService->getField('http_user'),
            $this->configService->getField('http_pass')
        );

        $sdkConfiguration->setShopInfo(
            \CreditAgricolePaymentGateway::SHOP_NAME,
            _PS_VERSION_
        );

        $sdkConfiguration->setPluginInfo(
            \CreditAgricolePaymentGateway::EXTENSION_HEADER_PLUGIN_NAME,
            \CreditAgricolePaymentGateway::VERSION
        );

        $sdkConfiguration->add(
            $configFactory->createConfig()
        );

        $sdkConfiguration = $this->addOptionalSepaConfiguration($sdkConfiguration);

        return $sdkConfiguration;
    }

    /**
     * Determines what type of configuration to build
     *
     * @return string
     * @since 2.1.0
     */
    private function resolveFactoryType()
    {
        switch ($this->configService->getType()) {
            case CreditCardTransaction::NAME:
                return CreditcardConfigurationFactory::class;
            case SepaCreditTransferTransaction::NAME:
            case SepaDirectDebitTransaction::NAME:
                return SepaConfigurationFactory::class;
            default:
                return GenericConfigurationFactory::class;
        }
    }

    /**
     * Adds a SEPA Credit Transfer configuration for those payments
     * that use it for refunds
     *
     * @param Config $sdkConfiguration
     * @return Config
     */
    private function addOptionalSepaConfiguration($sdkConfiguration)
    {
        if (!$this->requiresSepaCredit) {
            return $sdkConfiguration;
        }

        $sepaConfigService = new ShopConfigurationService(PaymentSepaCreditTransfer::TYPE);
        $sepaConfigFactory = new SepaConfigurationFactory($sepaConfigService);

        $sdkConfiguration->add(
            $sepaConfigFactory->createConfig()
        );

        return $sdkConfiguration;
    }
}
