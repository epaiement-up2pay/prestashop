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
use Wirecard\Credentials\Exception\MissedCredentialsException;

/**
 * Class MissedCredentialsExceptionTest
 * @package CredentialsTest\Constants
 * @coversDefaultClass \Wirecard\Credentials\Exception\MissedCredentialsException
 * @since 1.0.0
 */
class MissedCredentialsExceptionTest extends TestCase
{
    /**
     * @group unit
     * @small
     * @throws MissedCredentialsException
     */
    public function testMissedCredentialsException()
    {
        $this->expectException(MissedCredentialsException::class);
        $this->expectExceptionMessage("Following fields: [X, Y, Z] are required!");
        throw new MissedCredentialsException(['X', 'Y', 'Z']);
    }
}
