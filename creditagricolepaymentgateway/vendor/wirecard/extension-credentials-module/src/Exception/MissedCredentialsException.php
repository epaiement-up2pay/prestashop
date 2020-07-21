<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Exception;

use Exception;
use Throwable;

/**
 * Class MissedCredentialsException
 * @package Credentials\Exception
 * @since 1.0.0
 */
class MissedCredentialsException extends Exception
{
    /**
     * MissedCredentialsException constructor.
     * @param array $fields
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @since 1.0.0
     */
    public function __construct(array $fields, $message = "", $code = 0, Throwable $previous = null)
    {
        $message .= "Following fields: [" . implode(", ", $fields) . "] are required!";
        parent::__construct($message, $code, $previous);
    }
}
