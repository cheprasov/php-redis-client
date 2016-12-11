<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <cheprasov.84@ya.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\Integration\Version4x0;

include_once(__DIR__. '/../Version3x2/HashesCommandsTest.php');

use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x2\HashesCommandsTest as HashesCommandsTestVersion3x2;

/**
 * @see \RedisClient\Command\Traits\Version4x0\HashesCommandsTrait
 */
class HashesCommandsTest extends HashesCommandsTestVersion3x2 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_4x0_1;

    /**
     * @var RedisClient4x0
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

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

        $this->setExpectedException(ErrorResponseException::class);
        $Redis->hincrby('', 'null', 2);

        try {
            $Redis->hincrby('string', 'value', 2);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\HashesCommandsTrait::hstrlen
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
