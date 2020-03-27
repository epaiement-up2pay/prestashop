<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Config;

use Wirecard\Credentials\Exception\MissedCredentialsException;

/**
 * Class DefaultConfig
 * @package Credentials\Config
 * @since 1.0.0
 */
class DefaultConfig implements CredentialsConfigInterface
{
    /**
     * @var string
     */
    const ATTRIBUTE_MERCHANT_ACCOUNT_ID = 'merchant_account_id';

    /**
     * @var string
     */
    const ATTRIBUTE_BASE_URL = "base_url";

    /**
     * @var string
     */
    const ATTRIBUTE_SECRET = "secret";

    /**
     * @var string
     */
    const ATTRIBUTE_HTTP_USER = "http_user";

    /**
     * @var string
     */
    const ATTRIBUTE_HTTP_PASSWORD = "http_pass";

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $merchantAccountId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $httpUser;

    /**
     * @var string
     */
    private $httpPassword;

    /**
     * BaseConfig constructor.
     * @param array $credentials
     * @throws MissedCredentialsException
     * @since 1.0.0
     */
    public function __construct(array $credentials)
    {
        $missedKeys = array_diff($this->requiredAttributeList(), array_keys($credentials));
        if (count($missedKeys) > 0) {
            throw new MissedCredentialsException(array_values($missedKeys));
        }
        $this->loadFromCredentials($credentials);
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function getMerchantAccountId()
    {
        return $this->merchantAccountId;
    }

    /**
     * @inheritDoc
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @inheritDoc
     */
    public function getHttpUser()
    {
        return $this->httpUser;
    }

    /**
     * @inheritDoc
     */
    public function getHttpPassword()
    {
        return $this->httpPassword;
    }

    /**
     * @return array
     * @since 1.0.0
     */
    protected function requiredAttributeList()
    {
        return [
            self::ATTRIBUTE_MERCHANT_ACCOUNT_ID,
            self::ATTRIBUTE_SECRET,
            self::ATTRIBUTE_BASE_URL,
            self::ATTRIBUTE_HTTP_USER,
            self::ATTRIBUTE_HTTP_PASSWORD,
        ];
    }

    /**
     * @param array $credentials
     * @since 1.0.0
     */
    protected function loadFromCredentials(array $credentials)
    {
        $this->baseUrl = $credentials[self::ATTRIBUTE_BASE_URL];
        $this->merchantAccountId = $credentials[self::ATTRIBUTE_MERCHANT_ACCOUNT_ID];
        $this->secret = $credentials[self::ATTRIBUTE_SECRET];
        $this->httpUser = $credentials[self::ATTRIBUTE_HTTP_USER];
        $this->httpPassword = $credentials[self::ATTRIBUTE_HTTP_PASSWORD];
    }

    /**
     * @return array
     * @since 1.0.0
     */
    public function toArray()
    {
        return [
            self::ATTRIBUTE_BASE_URL => $this->getBaseUrl(),
            self::ATTRIBUTE_MERCHANT_ACCOUNT_ID => $this->getMerchantAccountId(),
            self::ATTRIBUTE_SECRET => $this->getSecret(),
            self::ATTRIBUTE_HTTP_USER => $this->getHttpUser(),
            self::ATTRIBUTE_HTTP_PASSWORD => $this->getHttpPassword()
        ];
    }
}
