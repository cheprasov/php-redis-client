<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <acheprasov84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\Integration\Version4x0;

use RedisClient\Exception\ErrorResponseException;

include_once(__DIR__. '/../Version3x2/StringsCommandsTest.php');

class StringsCommandsTest extends \Test\Integration\Version3x2\StringsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\StringsCommandsTrait::incrbyfloat
     */
    public function test_incrbyfloat() {
        $Redis = static::$Redis;

        $this->assertSame('7.7', $Redis->incrbyfloat('key', 7.7));
        $this->assertSame('11.7', $Redis->incrbyfloat('key', 4));
        $this->assertSame('-1', $Redis->incrbyfloat('key', -12.7));
        $this->assertSame('44.2', $Redis->incrbyfloat('integer', 2.2));
        $this->assertSame('44.2', $Redis->incrbyfloat('integer', 0));

        $this->assertSame('4.15159265', $Redis->incrbyfloat('float', 1.01));
        $this->assertSame('3.14159265', $Redis->incrbyfloat('float', -1.01));

        $this->expectException(ErrorResponseException::class);
        $this->assertSame('17.5', $Redis->incrbyfloat('bin', 17.5));

        $this->expectException(ErrorResponseException::class);
        $this->assertSame(8, $Redis->incrbyfloat('string', 8));

        $this->expectException(ErrorResponseException::class);
        $Redis->incrbyfloat('hash', 2.2);
    }
}
