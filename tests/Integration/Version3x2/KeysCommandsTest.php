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

include_once(__DIR__. '/../Version3x0/KeysCommandsTest.php');

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
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->dump('key'));

        $Redis->set('key', "\x00");
        $this->assertSame("\x00\x01\x00\x07\x00\xa4\xca\xf0\x3f\x64\xdd\x96\x4c", $Redis->dump('key'));

        $Redis->set('key', "1");
        $this->assertSame("\x00\xc0\x01\x07\x00\xd9\x4a\x32\x45\xd9\xcb\xc4\xe6", $Redis->dump('key'));

        $Redis->set('key', "10");
        $this->assertSame("\x00\xc0\x0a\x07\x00\x91\xad\x82\xb6\x06\x64\xb6\xa1", $Redis->dump('key'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame("\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05\x66\x69\x65\x6c\x64".
            "\x07\x05\x76\x61\x6c\x75\x65\xff\x07\x00\x93\xd1\xce\x4d\xd7\x96\x00\xd0", $Redis->dump('hash'));

    }

    public function test_restore() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\x01\x00\x07\x00\xa4\xca\xf0\x3f\x64\xdd\x96\x4c"));
        $this->assertSame("\x00", $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\xc0\x01\x07\x00\xd9\x4a\x32\x45\xd9\xcb\xc4\xe6", true));
        $this->assertSame('1', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\xc0\x0a\x07\x00\x91\xad\x82\xb6\x06\x64\xb6\xa1", true));
        $this->assertSame('10', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('hash', 0,
            "\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05\x66\x69\x65\x6c\x64".
            "\x07\x05\x76\x61\x6c\x75\x65\xff\x07\x00\x93\xd1\xce\x4d\xd7\x96\x00\xd0"
        ));
        $this->assertSame('value', $Redis->hget('hash', 'field'));

        try {
            $this->assertSame(true, $Redis->restore('key', 0, "\x00\x01\x00\x07\x00\xa4\xca\xf0\x3f\x64\xdd\x96\x4c"));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_rename() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->rename('key', 'foo'));
            $this->assertFalse('Expect Exception');
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
            $this->assertFalse('Expect Exception');
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

    public function test_migrate() {
        $Redis = static::$Redis;
        $Redis2 = static::$Redis2;
        $this->assertSame(true, $Redis2->flushall());

        list(, $host, $port) = explode(':', str_replace('/', '', static::TEST_REDIS_SERVER_2), 3);

        $this->assertSame(true, $Redis->set('one', 1));

        $this->assertSame(null, $Redis2->get('one'));
        $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, true));
        $this->assertSame('1', $Redis2->get('one'));
        $this->assertSame('1', $Redis->get('one'));

        $this->assertSame(true, $Redis->set('one', 11));

        try {
            $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, true));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, false, true));
        $this->assertSame('11', $Redis2->get('one'));
        $this->assertSame(null, $Redis->get('one'));

        $this->assertSame(true, $Redis->set('one', 1));
        $this->assertSame(true, $Redis->set('two', 2));
        $this->assertSame(true, $Redis->set('three', 3));

        $this->assertSame(true, $Redis->migrate($host, $port, ['one', 'two', 'three'], 0, 100, false, true));
        $this->assertSame('1', $Redis2->get('one'));
        $this->assertSame(null, $Redis->get('one'));
        $this->assertSame('2', $Redis2->get('two'));
        $this->assertSame(null, $Redis->get('two'));
        $this->assertSame('3', $Redis2->get('three'));
        $this->assertSame(null, $Redis->get('three'));
    }

}
