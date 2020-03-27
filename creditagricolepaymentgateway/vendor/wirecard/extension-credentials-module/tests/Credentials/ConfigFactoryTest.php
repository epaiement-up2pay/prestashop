<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace WirecardTest\Credentials;

use Wirecard\Credentials\Config\CredentialsConfigInterface;
use Wirecard\Credentials\Config\CredentialsCreditCardConfig;
use Wirecard\Credentials\PaymentMethod;
use Wirecard\Credentials\PaymentMethodRegistry;
use Wirecard\Credentials\Config\ConfigFactory;
use Wirecard\Credentials\Config\CreditCardConfig;
use Wirecard\Credentials\Config\DefaultConfig;
use Wirecard\Credentials\Exception\MissedCredentialsException;
use Wirecard\Credentials\Exception\InvalidPaymentMethodException;
use PHPUnit\Framework\TestCase;
use Generator;

/**
 * Class ConfigFactoryTest
 * @package CredentialsTest\Reader
 * @coversDefaultClass \Wirecard\Credentials\Config\ConfigFactory
 * @SuppressWarnings(PHPMD.LongVariable)
 * @since 1.0.0
 */
class ConfigFactoryTest extends TestCase
{
    /**
     * @group unit
     * @small
     * @covers ::createConfig
     * @throws InvalidPaymentMethodException
     * @throws MissedCredentialsException
     */
    public function testCreateConfigException()
    {
        $factory = new ConfigFactory();

        $this->expectException(MissedCredentialsException::class);
        $factory->createConfig(new PaymentMethod(PaymentMethodRegistry::TYPE_CREDIT_CARD), []);
        $this->expectException(InvalidPaymentMethodException::class);
        $factory->createConfig("InvalidType", []);
    }


    /**
     * @return array
     */
    public function getDefaultConfigCredentials()
    {
        return [
            DefaultConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID => "123456",
            DefaultConfig::ATTRIBUTE_BASE_URL => "https://base.wirecard.com",
            DefaultConfig::ATTRIBUTE_SECRET => "secret",
            DefaultConfig::ATTRIBUTE_HTTP_USER => "http_user",
            DefaultConfig::ATTRIBUTE_HTTP_PASSWORD => "http_password",
        ];
    }

    /**
     * @return array
     */
    public function getCreditCardConfigCredentials()
    {
        $creditCardCredentials = [
            CreditCardConfig::ATTRIBUTE_WPP_URL => "https://wpp.wirecard.com",
            CreditCardConfig::ATTRIBUTE_3D_SECRET => "topSecret",
            CreditCardConfig::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID => "123456",
        ];

        return array_merge($this->getDefaultConfigCredentials(), $creditCardCredentials);
    }

    /**
     * @return Generator
     * @throws InvalidPaymentMethodException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function createConfigDataProvider()
    {
        yield "create credit card config" => [
            new PaymentMethod(PaymentMethodRegistry::TYPE_CREDIT_CARD),
            $this->getCreditCardConfigCredentials(),
            CreditCardConfig::class,
            CredentialsCreditCardConfig::class
        ];

        $paymentMethodList = PaymentMethodRegistry::availablePaymentMethods();
        array_shift($paymentMethodList);
        foreach ($paymentMethodList as $paymentMethod) {
            yield "create default config {$paymentMethod}" => [
                new PaymentMethod($paymentMethod),
                $this->getDefaultConfigCredentials(),
                DefaultConfig::class,
                CredentialsConfigInterface::class
            ];
        }
    }

    /**
     * @group unit
     * @small
     * @covers ::createConfig
     * @dataProvider createConfigDataProvider
     * @param PaymentMethod $paymentMethod
     * @param array $credentials
     * @param string $configClass
     * @param string $configInterface
     * @throws MissedCredentialsException
     */
    public function testCreateConfig($paymentMethod, $credentials, $configClass, $configInterface)
    {
        $factory = new ConfigFactory();
        $config = $factory->createConfig($paymentMethod, $credentials);
        $this->assertInstanceOf($configClass, $config);
        $this->assertInstanceOf($configInterface, $config);
    }

    /**
     * @group unit
     * @small
     * @covers ::createConfigList
     * @throws InvalidPaymentMethodException
     * @throws MissedCredentialsException
     */
    public function testCreateConfigList()
    {
        $factory = new ConfigFactory();

        $result = $factory->createConfigList([
            PaymentMethodRegistry::TYPE_CREDIT_CARD => $this->getCreditCardConfigCredentials(),
            PaymentMethodRegistry::TYPE_PAYPAL => $this->getDefaultConfigCredentials(),
        ]);

        $this->assertTrue(is_array($result));
        $this->assertNotEmpty(is_array($result));
        $this->assertCount(2, $result);
        $this->assertArrayHasKey(PaymentMethodRegistry::TYPE_CREDIT_CARD, $result);
        $this->assertArrayHasKey(PaymentMethodRegistry::TYPE_PAYPAL, $result);
    }
}
