<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Reader;

/**
 * Interface ReaderInterface
 * @package Credentials\Reader
 * @since 1.0.0
 */
interface ReaderInterface
{
    /**
     * @return array
     * @since 1.0.0
     */
    public function toArray();
}
