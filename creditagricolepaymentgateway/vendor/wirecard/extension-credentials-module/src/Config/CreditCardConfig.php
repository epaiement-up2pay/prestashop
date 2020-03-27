<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Config;

/**
 * Class CreditCardConfig
 * @package Credentials\Config
 * @SuppressWarnings(PHPMD.LongVariable)
 * @since 1.0.0
 */
class CreditCardConfig extends DefaultConfig implements CredentialsCreditCardConfig
{
    /**
     * @var string
     */
    const ATTRIBUTE_WPP_URL = "wpp_url";
    /**
     * @var string
     */
    const ATTRIBUTE_3D_SECRET = "three_d_secret";
    /**
     * @var string
     */
    const ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID = "three_d_merchant_account_id";

    /**
     * @var string
     */
    private $wppUrl;

    /**
     * @var string
     */
    private $threeDSecret;

    /**
     * @var string
     */
    private $threeDMerchantAccountId;

    /**
     * @inheritDoc
     */
    public function getWppUrl()
    {
        return $this->wppUrl;
    }

    /**
     * @inheritDoc
     */
    public function getThreeDSecret()
    {
        return $this->threeDSecret;
    }

    /**
     * @inheritDoc
     */
    public function getThreeDMerchantAccountId()
    {
        return $this->threeDMerchantAccountId;
    }

    /**
     * @inheritDoc
     */
    protected function requiredAttributeList()
    {
        $requiredList = parent::requiredAttributeList();
        $requiredList[] = self::ATTRIBUTE_WPP_URL;
        $requiredList[] = self::ATTRIBUTE_3D_SECRET;
        $requiredList[] = self::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID;
        return $requiredList;
    }

    /**
     * @inheritDoc
     */
    protected function loadFromCredentials(array $credentials)
    {
        parent::loadFromCredentials($credentials);
        $this->wppUrl = $credentials[self::ATTRIBUTE_WPP_URL];
        $this->threeDMerchantAccountId = $credentials[self::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID];
        $this->threeDSecret = $credentials[self::ATTRIBUTE_3D_SECRET];
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray[self::ATTRIBUTE_WPP_URL] = $this->getWppUrl();
        $toArray[self::ATTRIBUTE_3D_MERCHANT_ACCOUNT_ID] = $this->getThreeDMerchantAccountId();
        $toArray[self::ATTRIBUTE_3D_SECRET] = $this->getThreeDSecret();
        return $toArray;
    }
}
