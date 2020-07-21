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

/**
 * Class InvalidXMLFormatException
 * @package Credentials\Exception
 * @since 1.0.0
 */
class InvalidXMLFormatException extends Exception
{
    /**
     * @var string
     */
    protected $message = "Provided XML not conforms xsd schema rules!";
}
