<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace WirecardTest\Exception;

use Wirecard\Credentials\PaymentMethodRegistry;
use PHPUnit\Framework\TestCase;
use Wirecard\Credentials\Exception\InvalidPaymentMethodException;

/**
 * Class InvalidPaymentMethodExceptionTest
 * @package CredentialsTest\Exception
 * @coversDefaultClass \Wirecard\Credentials\Exception\InvalidPaymentMethodException
 * @since 1.0.0
 */
class InvalidPaymentMethodExceptionTest extends TestCase
{
    /**
     * @group unit
     * @small
     * @throws InvalidPaymentMethodException
     */
    public function testInvalidPaymentMethodException()
    {
        $this->expectException(InvalidPaymentMethodException::class);
        $this->expectExceptionMessage("Invalid or unsupported payment method creditcard");
        throw new InvalidPaymentMethodException(PaymentMethodRegistry::TYPE_CREDIT_CARD);
    }
}
