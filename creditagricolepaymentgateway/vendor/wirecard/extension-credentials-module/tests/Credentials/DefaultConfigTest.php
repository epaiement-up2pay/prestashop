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
use Wirecard\Credentials\Config\DefaultConfig;
use Wirecard\Credentials\Exception\MissedCredentialsException;
use PHPUnit\Framework\TestCase;

/**
 * Class DefaultConfigTest
 * @package CredentialsTest\Reader
 * @coversDefaultClass \Wirecard\Credentials\Config\DefaultConfig
 * @since 1.0.0
 */
class DefaultConfigTest extends TestCase
{
    /**
     * @group unit
     * @small
     * @covers ::getPaymentMethod
     * @covers ::getBaseUrl
     * @covers ::getHttpUser
     * @covers ::getHttpPassword
     * @covers ::getMerchantAccountId
     * @covers ::getSecret
     * @throws MissedCredentialsException
     */
    public function testConstructor()
    {
        $credentials = [
            DefaultConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID => "123456",
            DefaultConfig::ATTRIBUTE_BASE_URL => "https://base.wirecard.com",
            DefaultConfig::ATTRIBUTE_SECRET => "secret",
            DefaultConfig::ATTRIBUTE_HTTP_USER => "http_user",
            DefaultConfig::ATTRIBUTE_HTTP_PASSWORD => "http_password",
        ];
        $defaultConfig = new DefaultConfig($credentials);
        $this->assertEquals($credentials[DefaultConfig::ATTRIBUTE_BASE_URL], $defaultConfig->getBaseUrl());
        $this->assertEquals(
            $credentials[DefaultConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID],
            $defaultConfig->getMerchantAccountId()
        );
        $this->assertEquals(
            $credentials[DefaultConfig::ATTRIBUTE_SECRET],
            $defaultConfig->getSecret()
        );
        $this->assertEquals(
            $credentials[DefaultConfig::ATTRIBUTE_HTTP_USER],
            $defaultConfig->getHttpUser()
        );
        $this->assertEquals(
            $credentials[DefaultConfig::ATTRIBUTE_HTTP_PASSWORD],
            $defaultConfig->getHttpPassword()
        );
        $this->assertInstanceOf(DefaultConfig::class, $defaultConfig);
        $this->assertInstanceOf(CredentialsConfigInterface::class, $defaultConfig);

        $this->expectException(MissedCredentialsException::class);
        new DefaultConfig([]);
    }

    /**
     * @group unit
     * @small
     * @covers ::toArray
     * @throws MissedCredentialsException
     */
    public function testToArray()
    {
        $credentials = [
            DefaultConfig::ATTRIBUTE_MERCHANT_ACCOUNT_ID => "123456",
            DefaultConfig::ATTRIBUTE_BASE_URL => "https://base.wirecard.com",
            DefaultConfig::ATTRIBUTE_SECRET => "secret",
            DefaultConfig::ATTRIBUTE_HTTP_USER => "http_user",
            DefaultConfig::ATTRIBUTE_HTTP_PASSWORD => "http_password",
        ];
        $defaultConfig = new DefaultConfig($credentials);
        $this->assertEquals($credentials, $defaultConfig->toArray());
    }
}
