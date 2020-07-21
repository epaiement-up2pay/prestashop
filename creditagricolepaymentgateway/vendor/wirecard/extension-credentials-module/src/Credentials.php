<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials;

use Wirecard\Credentials\Config\ConfigFactory;
use Wirecard\Credentials\Config\CredentialsConfigInterface;
use Wirecard\Credentials\Config\CredentialsContainer;
use Wirecard\Credentials\Config\CredentialsCreditCardConfig;
use Wirecard\Credentials\Exception\InvalidPaymentMethodException;
use Wirecard\Credentials\Reader\XMLReader;
use RuntimeException;

/**
 * Class Credentials
 * @package Credentials
 * @since 1.0.0
 */
class Credentials
{
    /**
     * @var Reader\ReaderInterface
     */
    private $reader;

    /**
     * @var array|CredentialsConfigInterface[]|CredentialsCreditCardConfig[]
     */
    private $config = [];

    /**
     * Credentials constructor.
     * @param string $credentialsFilePath
     * @throws Exception\InvalidXMLFormatException
     * @since 1.0.0
     */
    public function __construct($credentialsFilePath)
    {
        if (!is_readable($credentialsFilePath)) {
            throw new RuntimeException("File is not readable: " . $credentialsFilePath);
        }
        $this->reader = new XMLReader(
            file_get_contents($credentialsFilePath)
        );
    }

    /**
     * @param PaymentMethod | string $paymentMethod
     * @return CredentialsConfigInterface|CredentialsCreditCardConfig
     * @throws Exception\InvalidPaymentMethodException
     * @throws Exception\MissedCredentialsException
     * @since 1.0.0
     */
    public function getConfigByPaymentMethod(PaymentMethod $paymentMethod)
    {
        $config = $this->getConfig();
        $paymentMethodValue = (string)$paymentMethod;
        if (!isset($config[$paymentMethodValue])) {
            throw new InvalidPaymentMethodException($paymentMethodValue);
        }
        return $config[$paymentMethodValue];
    }

    /**
     * @return CredentialsConfigInterface[]|CredentialsCreditCardConfig[]|CredentialsContainer[]
     * @throws Exception\InvalidPaymentMethodException
     * @throws Exception\MissedCredentialsException
     * @since 1.0.0
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            $this->config = (new ConfigFactory())->createConfigList(
                $this->reader->toArray()
            );
        }
        return $this->config;
    }
}
