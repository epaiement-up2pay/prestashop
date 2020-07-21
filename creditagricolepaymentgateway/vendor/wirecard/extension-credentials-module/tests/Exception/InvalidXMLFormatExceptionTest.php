<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace WirecardTest\Exception;

use PHPUnit\Framework\TestCase;
use Wirecard\Credentials\Exception\InvalidXMLFormatException;

/**
 * Class InvalidXMLFormatExceptionTest
 * @package CredentialsTest\Exception
 * @coversDefaultClass \Wirecard\Credentials\Exception\InvalidXMLFormatException
 * @since 1.0.0
 */
class InvalidXMLFormatExceptionTest extends TestCase
{
    /**
     * @group unit
     * @small
     * @throws InvalidXMLFormatException
     */
    public function testInvalidXMLFormatException()
    {
        $this->expectException(InvalidXMLFormatException::class);
        $this->expectExceptionMessage("Provided XML not conforms xsd schema rules!");
        throw new InvalidXMLFormatException();
    }
}
