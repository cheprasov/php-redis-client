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
namespace Test\Integration\Version2x6;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Exception\ErrorResponseException;

/**
 * @see KeysCommandsTrait
 */
class KeysCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;
    const TEST_REDIS_SERVER_2 = TEST_REDIS_SERVER_2x6_2;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis2;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
        static::$Redis2 = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_2,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass() {
        static::$Redis->flushall();
        static::$Redis2->flushall();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
        static::$Redis2->flushall();
    }


    public function test_del() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->del('key'));
        $this->assertSame(0, $Redis->del(['key', 'key1', 'key2']));
        $this->assertSame(0, $Redis->del(['key', 'key', 'key']));

        $Redis->set('key', 'value');

        $this->assertSame(1, $Redis->del('key'));
        $this->assertSame(0, $Redis->del(['key', 'key1', 'key2']));
        $this->assertSame(0, $Redis->del(['key', 'key', 'key']));

        $Redis->set('key', 'value');

        $this->assertSame(1, $Redis->del(['key', 'key1', 'key2']));
        $this->assertSame(0, $Redis->del(['key', 'key', 'key']));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->del(['key', 'key', 'key']));
        $this->assertSame(0, $Redis->del(['key', 'key', 'key']));
    }

    public function test_dump() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->dump('key'));

        $Redis->set('key', "\x00");
        $this->assertSame("\x00\x01\x00\x06\x00\xcd\x15\x4d\x4c\x99\x42\x7f\xc5", $Redis->dump('key'));

        $Redis->set('key', "1");
        $this->assertSame("\x00\xc0\x01\x06\x00\xb0\x95\x8f6\$T-o", $Redis->dump('key'));

        $Redis->set('key', "10");
        $this->assertSame("\x00\xc0\n\x06\x00\xf8r?\xc5\xfb\xfb_(", $Redis->dump('key'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame("\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05field\x07\x05value\xff\x06\x00\xfa\x0es>*\x09\xe9Y", $Redis->dump('hash'));
    }

    public function test_exists() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->exists('key'));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->exists('key'));

        $Redis->del('key');
        $this->assertSame(0, $Redis->exists('key'));
    }

    public function test_expire() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->expire('key', 10));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->expire('key', 10));
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));

        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));

        $this->assertSame(1, $Redis->expire('key', 100));
        $this->assertGreaterThanOrEqual(99, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(100, $Redis->ttl('key'));
    }

    public function test_expireat() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->expireat('key', 10 + time()));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->expireat('key', 10 + time()));
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));

        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));

        $this->assertSame(1, $Redis->expireat('key', 100 + time()));
        $this->assertGreaterThanOrEqual(99, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(100, $Redis->ttl('key'));
    }

    public function test_keys() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->keys('*'));

        $this->assertSame(true, $Redis->mset(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4]));

        $keys = $Redis->keys('*'); sort($keys);
        $this->assertSame(['four', 'one', 'three', 'two'], $keys);

        $keys = $Redis->keys('*o*'); sort($keys);
        $this->assertSame(['four', 'one', 'two'], $keys);

        $keys = $Redis->keys('t??'); sort($keys);
        $this->assertSame(['two'], $keys);

        $keys = $Redis->keys('[to][wn][oe]'); sort($keys);
        $this->assertSame(['one', 'two'], $keys);

        $keys = $Redis->keys('[a-g]*'); sort($keys);
        $this->assertSame(['four'], $keys);

        $keys = $Redis->keys('[^t]*'); sort($keys);
        $this->assertSame(['four', 'one'], $keys);
    }

    public function test_migrate() {
        $Redis = static::$Redis;
        $Redis2 = static::$Redis2;

        $this->assertSame(true, $Redis2->flushall());

        list(, $host, $port) = explode(':', str_replace('/', '', static::TEST_REDIS_SERVER_2), 3);

        $this->assertSame(true, $Redis->set('one', 1));

        $this->assertSame(null, $Redis2->get('one'));
        $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100));
        $this->assertSame('1', $Redis2->get('one'));
        $this->assertSame(null, $Redis->get('one'));

        $this->assertSame(true, $Redis->set('one', 11));

        try {
            $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_move() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('one', 1));
        $this->assertSame('1', $Redis->get('one'));
        $this->assertSame(1, $Redis->move('one', 1));
        $this->assertSame(null, $Redis->get('one'));
        $this->assertSame(true, $Redis->select(1));
        $this->assertSame('1', $Redis->get('one'));

        $this->assertSame(true, $Redis->select(0));
        $this->assertSame(true, $Redis->set('one', 11));
        $this->assertSame('11', $Redis->get('one'));
        $this->assertSame(0, $Redis->move('one', 1));
        $this->assertSame('11', $Redis->get('one'));

        $this->assertSame(0, $Redis->move('two', 1));
    }

    public function test_object() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->lpush('mylist', 'Hello World 1'));
        $this->assertSame(2, $Redis->lpush('mylist', 'Hello World 2'));
        $this->assertSame(3, $Redis->lpush('mylist', 'Hello World 3'));
        $this->assertSame(4, $Redis->lpush('mylist', 'Hello World 4'));
        $this->assertSame(1, $Redis->object('REFCOUNT', 'mylist'));
        $this->assertSame('ziplist', $Redis->object('ENCODING', 'mylist'));
        $this->assertTrue(is_int($Redis->object('IDLETIME', 'mylist')));

        $this->assertSame(true, $Redis->set('foo', 1000));
        $this->assertSame('int', $Redis->object('encoding', 'foo'));
        $this->assertSame(7, $Redis->append('foo', 'bar'));
        $this->assertSame('1000bar', $Redis->get('foo'));
        $this->assertSame('raw', $Redis->object('encoding', 'foo'));
    }

    public function test_persist() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('foo', 'bar', 10));
        $this->assertLessThanOrEqual(10, $Redis->ttl('foo'));
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('foo'));
        $this->assertSame(1, $Redis->persist('foo'));
        $this->assertSame(0, $Redis->persist('foo'));
        $this->assertSame(0, $Redis->persist('bar'));
    }

    public function test_pexpire() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->pexpire('key', 10000));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->pexpire('key', 10000));
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));

        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));

        $this->assertSame(1, $Redis->pexpire('key', 100000));
        $this->assertGreaterThanOrEqual(99, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(100, $Redis->ttl('key'));
    }

    public function test_pexpireat() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->pexpireat('key', 1000 * (10 + time())));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->pexpireat('key', 1000 * (10 + time())));
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));

        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));

        $this->assertSame(1, $Redis->pexpireat('key', 1000 * (100 + time())));
        $this->assertGreaterThanOrEqual(99, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(100, $Redis->ttl('key'));
    }

    public function test_pttl() {
        $Redis = static::$Redis;

        $this->assertSame(-1, $Redis->pttl('key'));
        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->pttl('key'));
        $Redis->pexpire('key', 1000);
        $this->assertGreaterThanOrEqual(999, $Redis->pttl('key'));
        $this->assertLessThanOrEqual(1000, $Redis->pttl('key'));
    }

    public function test_randomkey() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->randomkey());
        $Redis->set('key', 'value');
        $this->assertSame('key', $Redis->randomkey());
        $this->assertSame('key', $Redis->randomkey());
        $Redis->set('foo', 'value');
        $Redis->set('bar', 'value');
        $this->assertTrue(in_array($Redis->randomkey(), ['foo', 'bar', 'key']));
        $this->assertTrue(in_array($Redis->randomkey(), ['foo', 'bar', 'key']));
        $this->assertTrue(in_array($Redis->randomkey(), ['foo', 'bar', 'key']));
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

        try {
            $Redis->rename('key', 'key');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

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

        try {
            $Redis->renamenx('key', 'key');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(1, $Redis->renamenx('key', 'foo'));
        $this->assertSame(0, $Redis->exists('key'));
        $this->assertSame(1, $Redis->exists('foo'));

        $this->assertSame(0, $Redis->renamenx('foo', 'bar'));
        $this->assertSame(1, $Redis->exists('foo'));
        $this->assertSame(1, $Redis->exists('bar'));
        $this->assertSame('value2', $Redis->get('bar'));
    }

    public function test_restore() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\x01\x00\x06\x00\xcd\x15\x4d\x4c\x99\x42\x7f\xc5"));
        $this->assertSame("\x00", $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key1', 0, "\x00\xc0\x01\x06\x00\xb0\x95\x8f6\$T-o"));
        $this->assertSame('1', $Redis->get('key1'));

        $this->assertSame(true, $Redis->restore('key2', 0, "\x00\xc0\n\x06\x00\xf8r?\xc5\xfb\xfb_("));
        $this->assertSame('10', $Redis->get('key2'));

        $this->assertSame(true, $Redis->restore('hash3', 0, "\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05field\x07\x05value\xff\x06\x00\xfa\x0es>*\x09\xe9Y"));
        $this->assertSame('value', $Redis->hget('hash3', 'field'));
    }

    public function test_sort() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->lpush('ages', '18'));
        $this->assertSame(2, $Redis->lpush('ages', '17'));
        $this->assertSame(3, $Redis->lpush('ages', '5'));
        $this->assertSame(4, $Redis->lpush('ages', '100'));
        $this->assertSame(5, $Redis->lpush('ages', '99'));

        $this->assertSame(['5', '17', '18', '99', '100'], $Redis->sort('ages'));
        $this->assertSame(['100', '17', '18', '5', '99'], $Redis->sort('ages', null, null, null, null, true));

        $this->assertSame(1, $Redis->lpush('users', '1'));
        $this->assertSame(2, $Redis->lpush('users', '2'));
        $this->assertSame(3, $Redis->lpush('users', '3'));
        $this->assertSame(4, $Redis->lpush('users', '4'));
        $this->assertSame(5, $Redis->lpush('users', '5'));

        $this->assertSame(true, $Redis->set('user:1:age', '18'));
        $this->assertSame(true, $Redis->set('user:2:age', '6'));
        $this->assertSame(true, $Redis->set('user:3:age', '23'));
        $this->assertSame(true, $Redis->set('user:4:age', '8'));
        $this->assertSame(true, $Redis->set('user:5:age', '100'));

        $this->assertSame(['2', '4', '1', '3', '5'], $Redis->sort('users', 'user:*:age'));
        $this->assertSame(5, $Redis->sort('users', 'user:*:age', null, null, null, null, 'list'));
        $this->assertSame(['2', '4', '1', '3', '5'], $Redis->lrange('list', 0, -1));
    }

    public function test_ttl() {
        $Redis = static::$Redis;

        $this->assertSame(-1, $Redis->ttl('key'));
        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));
        $Redis->expire('key', 10);
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));
    }

    public function test_type() {
        $Redis = static::$Redis;

        $this->assertSame('none', $Redis->type('key'));
        $this->assertSame('none', $Redis->type(''));

        $Redis->set('string', 'value');
        $this->assertSame('string', $Redis->type('string'));

        $Redis->lpush('list', 'value');
        $this->assertSame('list', $Redis->type('list'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame('hash', $Redis->type('hash'));

        $Redis->zadd('zset', ['member' => 100]);
        $this->assertSame('zset', $Redis->type('zset'));

        $Redis->sadd('set', 'member');
        $this->assertSame('set', $Redis->type('set'));
    }

}
