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
namespace Test\Integration\Version3x2;

include_once(__DIR__. '/../Version3x0/HashesCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version3x2\HashesCommandsTrait
 */
class HashesCommandsTest extends \Test\Integration\Version3x0\HashesCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\HashesCommandsTrait::hincrby
     */
    public function test_hincrby() {
        $Redis = static::$Redis;

        $this->assertSame(11, $Redis->hincrby('key-does-not-exist', 'field', 11));
        $this->assertSame(11, $Redis->hincrby('hash', 'field-does-not-exist', 11));
        $this->assertSame(-11, $Redis->hincrby('key-does-not-exist-2', 'field', -11));
        $this->assertSame(-11, $Redis->hincrby('hash', 'field-does-not-exist-2', -11));

        try {
            $this->assertSame(2, $Redis->hincrby('hash', 'string', 2));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        try {
            $this->assertSame(1, $Redis->hincrby('hash', 'float', 3));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        try {
            // I don't know why it happens, but it is real Redis behavior
            $this->assertSame(3, $Redis->hincrby('hash', 'bin', 3));
            //$this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(0, $Redis->hset('hash', 'null', 4));
        $this->assertSame(3, $Redis->hincrby('hash', 'null', -1));
        $this->assertSame(0, $Redis->hset('hash', 'empty', 0));
        $this->assertSame(5, $Redis->hincrby('hash', 'empty', 5));
        $this->assertSame(-10, $Redis->hincrby('hash', 'empty', -15));
        $this->assertSame(48, $Redis->hincrby('hash', 'integer', 6));
        $this->assertSame(0, $Redis->hincrby('hash', 'integer', -48));

        $this->expectException(ErrorResponseException::class);
        $Redis->hincrby('', 'null', 2);

        try {
            $Redis->hincrby('string', 'value', 2);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x2\HashesCommandsTrait::hstrlen
     */
    public function test_hstrlen() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->hstrlen('hash', 'some-field'));
        $this->assertSame(1, $Redis->hsetnx('hash', 'some-field', 'good'));
        $this->assertSame(4, $Redis->hstrlen('hash', 'some-field'));

        try {
            $Redis->hstrlen('string', 'field');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
