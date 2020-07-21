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
 * Interface CredentialsConfigInterface
 * @package Credentials\Config
 * @since 1.0.0
 */
interface CredentialsConfigInterface extends CredentialsContainer
{
    /**
     * @return string
     * @since 1.0.0
     */
    public function getMerchantAccountId();

    /**
     * @return string
     * @since 1.0.0
     */
    public function getSecret();

    /**
     * @return string
     * @since 1.0.0
     */
    public function getBaseUrl();

    /**
     * @return string
     * @since 1.0.0
     */
    public function getHttpUser();


    /**
     * @return string
     * @since 1.0.0
     */
    public function getHttpPassword();
}
