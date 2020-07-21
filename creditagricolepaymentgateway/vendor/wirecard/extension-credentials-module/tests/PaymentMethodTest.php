<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace WirecardTest;

use Wirecard\Credentials\Exception\InvalidPaymentMethodException;
use Wirecard\Credentials\PaymentMethod;
use Wirecard\Credentials\PaymentMethodRegistry;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class PaymentMethodTest
 * @package CredentialsTest
 * @coversDefaultClass \Wirecard\Credentials\PaymentMethod
 * @SuppressWarnings(PHPMD.LongVariable)
 * @since 1.0.0
 */
class PaymentMethodTest extends TestCase
{
    /**
     * @return Generator
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function dataProviderAvailablePaymentMethods()
    {
        foreach (PaymentMethodRegistry::availablePaymentMethods() as $pm) {
            yield $pm => [$pm];
        }
    }

    /**
     * @group unit
     * @small
     * @dataProvider dataProviderAvailablePaymentMethods
     * @param string $value
     * @throws InvalidPaymentMethodException
     */
    public function testConstructor($value)
    {
        $paymentMethod = new PaymentMethod($value);
        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertEquals($value, (string)$paymentMethod);
        $this->assertEquals($value, (string)$paymentMethod);
    }

    /**
     * @return Generator
     */
    public function dataProviderInvalidTypeValues()
    {
        yield "string" => ["fooBarFoo"];
        yield "null" => [null];
        yield "null_string" => ["null"];
        yield "empty_string" => [""];
        yield "negative_number" => [-123];
        yield "positive_number" => [123];
        yield "bool_false" => [false];
        yield "bool_true" => [true];
    }

    /**
     * @group unit
     * @small
     * @dataProvider dataProviderInvalidTypeValues
     * @param mixed $invalidValue
     * @throws InvalidPaymentMethodException
     */
    public function testConstructorException($invalidValue)
    {
        $this->expectException(InvalidPaymentMethodException::class);
        new PaymentMethod($invalidValue);
    }

    /**
     * @group unit
     * @small
     * @covers ::equalsTo
     * @throws InvalidPaymentMethodException
     */
    public function testEqualsTo()
    {
        $paymentMethod = new PaymentMethod(PaymentMethodRegistry::TYPE_IDEAL);
        $this->assertTrue($paymentMethod->equalsTo(new PaymentMethod(PaymentMethodRegistry::TYPE_IDEAL)));
        $this->assertFalse($paymentMethod->equalsTo(new PaymentMethod(PaymentMethodRegistry::TYPE_SOFORTBANKING)));
    }
}
