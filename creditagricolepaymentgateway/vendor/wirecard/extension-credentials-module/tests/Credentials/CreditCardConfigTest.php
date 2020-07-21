<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace WirecardTest\Reader;

use Wirecard\Credentials\Config\CredentialsConfigInterface;
use Wirecard\Credentials\Config\CredentialsCreditCardConfig;
use Wirecard\Credentials\Config\CreditCardConfig;
use Wirecard\Credentials\Config\DefaultConfig;
use Wirecard\Credentials\Exception\MissedCredentialsException;
use PHPUnit\Framework\TestCase;

/**
 * Class CreditCardConfigTest
 * @package CredentialsTest\Reader
 * @coversDefaultClass \Wirecard\Credentials\Config\CreditCardConfig
 * @since 1.0.0
 */
class CreditCardConfigTest extends TestCase
{
    /**
     * @return array
     */
    private function getDefaultCredentials()
    {
        return [
            DefaultConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID => "123456",
            DefaultConfig::ATTRIBUTE_BASE_URL => "https://api.wirecard.com",
            DefaultConfig::ATTRIBUTE_SECRET => "secret",
            DefaultConfig::ATTRIBUTE_HTTP_USER => "http_user",
            DefaultConfig::ATTRIBUTE_HTTP_PASSWORD => "http_password",

            CreditCardConfig::ATTRIBUTE_WPP_URL => "https://wpp.wirecard.com",
            CreditCardConfig::ATTRIBUTE_3D_SECRET => "topSecret",
            CreditCardConfig::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID => "123456",
        ];
    }

    /**
     * @group unit
     * @small
     * @covers ::getThreeDSecret
     * @covers ::getThreeDMerchantAccountId
     * @covers ::getWppUrl
     * @throws MissedCredentialsException
     */
    public function testConstructor()
    {
        $credentials = $this->getDefaultCredentials();
        $creditCardConfig = new CreditCardConfig($credentials);
        $this->assertEquals($credentials[CreditCardConfig::ATTRIBUTE_BASE_URL], $creditCardConfig->getBaseUrl());
        $this->assertEquals(
            $credentials[CreditCardConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID],
            $creditCardConfig->getMerchantAccountId()
        );
        $this->assertEquals(
            $credentials[CreditCardConfig::ATTRIBUTE_WPP_URL],
            $creditCardConfig->getWppUrl()
        );
        $this->assertEquals(
            $credentials[CreditCardConfig::ATTRIBUTE_3D_SECRET],
            $creditCardConfig->getThreeDSecret()
        );
        $this->assertEquals(
            $credentials[CreditCardConfig::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID],
            $creditCardConfig->getThreeDMerchantAccountId()
        );
        $this->assertInstanceOf(CreditCardConfig::class, $creditCardConfig);
        $this->assertInstanceOf(CredentialsCreditCardConfig::class, $creditCardConfig);
        $this->assertInstanceOf(CredentialsConfigInterface::class, $creditCardConfig);
    }

    /**
     * @group unit
     * @small
     * @throws MissedCredentialsException
     */
    public function testConstructorException()
    {
        $this->expectException(MissedCredentialsException::class);
        new CreditCardConfig([]);
    }

    /**
     * @group unit
     * @small
     * @covers ::toArray
     * @throws MissedCredentialsException
     */
    public function testToArray()
    {
        $credentials = $this->getDefaultCredentials();
        $creditCardConfig = new CreditCardConfig($credentials);
        $this->assertEquals($credentials, $creditCardConfig->toArray());
    }
}
