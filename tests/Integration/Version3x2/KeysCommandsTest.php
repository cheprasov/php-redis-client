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
namespace Test\Integration\Version3x2;

include(__DIR__. '/../Version3x0/KeysCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x0\KeysCommandsTest as KeysCommandsTestVersion3x0;

/**
 * @see KeysCommandsTrait
 */
class KeysCommandsTest extends KeysCommandsTestVersion3x0 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;
    const TEST_REDIS_SERVER_2 = TEST_REDIS_SERVER_3x2_2;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
        static::$Redis2 = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_2,
            'timeout' => 2,
        ]);
    }

    public function test_dump() {
        //todo
    }

    public function test_restore() {
        //todo
    }

    public function test_rename() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->rename('key', 'foo'));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $Redis->set('key', 'value1');
        $Redis->set('bar', 'value2');

        $this->assertSame(true, $Redis->rename('key', 'key'));

        $this->assertSame(true, $Redis->rename('key', 'foo'));
        $this->assertSame(0, $Redis->exists('key'));
        $this->assertSame(1, $Redis->exists('foo'));

        $this->assertSame(true, $Redis->rename('foo', 'bar'));
        $this->assertSame(0, $Redis->exists('foo'));
        $this->assertSame(1, $Redis->exists('bar'));
        $this->assertSame('value1', $Redis->get('bar'));
    }


    public function test_renamenx() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(0, $Redis->renamenx('key', 'foo'));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $Redis->set('key', 'value1');
        $Redis->set('bar', 'value2');

        $this->assertSame(0, $Redis->renamenx('key', 'key'));

        $this->assertSame(1, $Redis->renamenx('key', 'foo'));
        $this->assertSame(0, $Redis->exists('key'));
        $this->assertSame(1, $Redis->exists('foo'));

        $this->assertSame(0, $Redis->renamenx('foo', 'bar'));
        $this->assertSame(1, $Redis->exists('foo'));
        $this->assertSame(1, $Redis->exists('bar'));
        $this->assertSame('value2', $Redis->get('bar'));
    }

    public function test_object() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->lpush('mylist', 'Hello World 1'));
        $this->assertSame(2, $Redis->lpush('mylist', 'Hello World 2'));
        $this->assertSame(3, $Redis->lpush('mylist', 'Hello World 3'));
        $this->assertSame(4, $Redis->lpush('mylist', 'Hello World 4'));
        $this->assertSame(1, $Redis->object('REFCOUNT', 'mylist'));
        $this->assertSame('quicklist', $Redis->object('ENCODING', 'mylist'));
        $this->assertTrue(is_int($Redis->object('IDLETIME', 'mylist')));

        $this->assertSame(true, $Redis->set('foo', 1000));
        $this->assertSame('int', $Redis->object('encoding', 'foo'));
        $this->assertSame(7, $Redis->append('foo', 'bar'));
        $this->assertSame('1000bar', $Redis->get('foo'));
        $this->assertSame('raw', $Redis->object('encoding', 'foo'));
    }

}
