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
namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see ListsCommandsTrait
 */
class ListsCommandsTest extends AbstractCommandsTest {

    public function test_blpop() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list1', 10));
        $this->assertSame(2, $Redis->rpush('list1', 11));
        $this->assertSame(3, $Redis->rpush('list1', 12));

        $this->assertSame(1, $Redis->rpush('list2', 20));
        $this->assertSame(2, $Redis->rpush('list2', 21));
        $this->assertSame(3, $Redis->rpush('list2', 22));

        $this->assertSame(['list2' => '20'], $Redis->blpop(['list2', 'list1'], 1));
        $this->assertSame(['list1' => '10'], $Redis->blpop(['list1', 'list2'], 1));
        $this->assertSame(['list1' => '11'], $Redis->blpop(['list1', 'list2'], 1));
        $this->assertSame(['list1' => '12'], $Redis->blpop(['list1', 'list2'], 1));
        $this->assertSame(['list2' => '21'], $Redis->blpop(['list1', 'list2'], 1));

        $time = microtime(true);
        $this->assertSame(null, $Redis->blpop(['list3', 'list4'], 1));
        $timeout = microtime(true) - $time;
        $this->assertGreaterThanOrEqual(1, $timeout);
        $this->assertLessThanOrEqual(1.5, $timeout);

        $Redis->set('foo', 'bar');
        try {
            $Redis->blpop('foo', 1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_brpop() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list1', 10));
        $this->assertSame(2, $Redis->rpush('list1', 11));
        $this->assertSame(3, $Redis->rpush('list1', 12));

        $this->assertSame(1, $Redis->rpush('list2', 20));
        $this->assertSame(2, $Redis->rpush('list2', 21));
        $this->assertSame(3, $Redis->rpush('list2', 22));

        $this->assertSame(['list2' => '22'], $Redis->brpop(['list2', 'list1'], 1));
        $this->assertSame(['list1' => '12'], $Redis->brpop(['list1', 'list2'], 1));
        $this->assertSame(['list1' => '11'], $Redis->brpop(['list1', 'list2'], 1));
        $this->assertSame(['list1' => '10'], $Redis->brpop(['list1', 'list2'], 1));
        $this->assertSame(['list2' => '21'], $Redis->brpop(['list1', 'list2'], 1));

        $time = microtime(true);
        $this->assertSame(null, $Redis->brpop(['list3', 'list4'], 1));
        $timeout = microtime(true) - $time;
        $this->assertGreaterThanOrEqual(1, $timeout);
        $this->assertLessThanOrEqual(1.5, $timeout);

        $Redis->set('foo', 'bar');
        try {
            $Redis->brpop('foo', 1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_brpoplpush() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list1', 10));
        $this->assertSame(2, $Redis->rpush('list1', 11));
        $this->assertSame(3, $Redis->rpush('list1', 12));

        $this->assertSame(1, $Redis->rpush('list2', 20));
        $this->assertSame(2, $Redis->rpush('list2', 21));
        $this->assertSame(3, $Redis->rpush('list2', 22));

        $this->assertSame('12', $Redis->brpoplpush('list1', 'list2', 1));
        $this->assertSame('11', $Redis->brpoplpush('list1', 'list2', 1));
        $this->assertSame('10', $Redis->brpoplpush('list1', 'list2', 1));
        $this->assertSame('22', $Redis->brpoplpush('list2', 'list1', 1));

        $time = microtime(true);
        $this->assertSame(null, $Redis->brpoplpush('list3', 'list4', 1));
        $timeout = microtime(true) - $time;
        $this->assertGreaterThanOrEqual(1, $timeout);
        $this->assertLessThanOrEqual(1.5, $timeout);

        $Redis->set('foo', 'bar');
        try {
            $Redis->brpoplpush('foo', 'bar', 1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
        try {
            $Redis->brpoplpush('list2', 'foo', 1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lindex() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 111));
        $this->assertSame(2, $Redis->rpush('list', 222));
        $this->assertSame(3, $Redis->rpush('list', 333));
        $this->assertSame(4, $Redis->rpush('list', 444));
        $this->assertSame(5, $Redis->rpush('list', 555));

        $this->assertSame('111', $Redis->lindex('list', 0));
        $this->assertSame('333', $Redis->lindex('list', 2));
        $this->assertSame('555', $Redis->lindex('list', 4));
        $this->assertSame('555', $Redis->lindex('list', -1));
        $this->assertSame('444', $Redis->lindex('list', -2));
        $this->assertSame(null, $Redis->lindex('list', -20));
        $this->assertSame(null, $Redis->lindex('list', 20));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lindex('foo', 1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_linsert() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(2, $Redis->rpush('list', 200));
        $this->assertSame(3, $Redis->rpush('list', 300));

        $this->assertSame(4, $Redis->linsert('list', true, '200', 250));
        $this->assertSame(5, $Redis->linsert('list', true, 300, 250));
        $this->assertSame(6, $Redis->linsert('list', false, 300, 300));
        $this->assertSame(7, $Redis->linsert('list', true, 300, 300));
        $this->assertSame(-1, $Redis->linsert('list', false, 99, 80));

        $this->assertSame(0, $Redis->linsert('foo', false, 99, 80));

        $Redis->set('foo', 'bar');
        try {
            $Redis->linsert('foo', true, 400, 400);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_llen() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->llen('list'));
        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(1, $Redis->llen('list'));
        $this->assertSame(2, $Redis->rpush('list', 200));
        $this->assertSame(2, $Redis->llen('list'));
        $this->assertSame(3, $Redis->rpush('list', 300));
        $this->assertSame(3, $Redis->llen('list'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->llen('foo');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lpop() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(2, $Redis->rpush('list', 200));
        $this->assertSame(3, $Redis->rpush('list', 300));
        $this->assertSame(['100', '200', '300'], $Redis->lrange('list', 0, -1));

        $this->assertSame('100', $Redis->lpop('list'));
        $this->assertSame('200', $Redis->lpop('list'));
        $this->assertSame(['300'], $Redis->lrange('list', 0, -1));
        $this->assertSame('300', $Redis->lpop('list'));
        $this->assertSame([], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lpop('foo');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lpush() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->lpush('list', 100));
        $this->assertSame(2, $Redis->lpush('list', 200));
        $this->assertSame(['200', '100'], $Redis->lrange('list', 0, -1));
        $this->assertSame(3, $Redis->lpush('list', 300));
        $this->assertSame(['300', '200', '100'], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lpush('foo', 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lpushx() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->lpushx('list', 100));
        $this->assertSame(1, $Redis->lpush('list', 100));
        $this->assertSame(2, $Redis->lpushx('list', 200));
        $this->assertSame(['200', '100'], $Redis->lrange('list', 0, -1));
        $this->assertSame(3, $Redis->lpushx('list', 300));
        $this->assertSame(['300', '200', '100'], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lpushx('foo', 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lrange() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 10));
        $this->assertSame(2, $Redis->rpush('list', 20));
        $this->assertSame(3, $Redis->rpush('list', 30));
        $this->assertSame(4, $Redis->rpush('list', 40));
        $this->assertSame(5, $Redis->rpush('list', 50));

        $this->assertSame(['10', '20', '30', '40', '50'], $Redis->lrange('list', 0, -1));
        $this->assertSame(['40', '50'], $Redis->lrange('list', -2, -1));
        $this->assertSame(['10', '20', '30'], $Redis->lrange('list', 0, 2));
        $this->assertSame(['10'], $Redis->lrange('list', 0, 0));
        $this->assertSame([], $Redis->lrange('list', 10, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lrange('foo', 0, -1);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lrem() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 10));
        $this->assertSame(2, $Redis->rpush('list', 50));
        $this->assertSame(3, $Redis->rpush('list', 20));
        $this->assertSame(4, $Redis->rpush('list', 30));
        $this->assertSame(5, $Redis->rpush('list', 50));
        $this->assertSame(6, $Redis->rpush('list', 40));
        $this->assertSame(7, $Redis->rpush('list', 50));
        $this->assertSame(8, $Redis->rpush('list', 20));
        $this->assertSame(9, $Redis->rpush('list', 10));
        $this->assertSame(10, $Redis->rpush('list', 1));

        $this->assertSame(2, $Redis->lrem('list', 2, '50'));
        $this->assertSame(['10', '20', '30', '40', '50', '20', '10', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(1, $Redis->lrem('list', -1, '20'));
        $this->assertSame(['10', '20', '30', '40', '50', '10', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(2, $Redis->lrem('list', 0, '10'));
        $this->assertSame(['20', '30', '40', '50', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(0, $Redis->lrem('list', 0, '100'));
        $this->assertSame(['20', '30', '40', '50', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(0, $Redis->lrem('list', -10, '100'));
        $this->assertSame(['20', '30', '40', '50', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(1, $Redis->lrem('list', -10, '20'));
        $this->assertSame(['30', '40', '50', '1'], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->lrem('foo', 0, 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_lset() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 10));
        $this->assertSame(2, $Redis->rpush('list', 50));
        $this->assertSame(3, $Redis->rpush('list', 20));
        $this->assertSame(4, $Redis->rpush('list', 30));
        $this->assertSame(5, $Redis->rpush('list', 50));
        $this->assertSame(6, $Redis->rpush('list', 40));
        $this->assertSame(7, $Redis->rpush('list', 50));
        $this->assertSame(8, $Redis->rpush('list', 20));
        $this->assertSame(9, $Redis->rpush('list', 10));
        $this->assertSame(10, $Redis->rpush('list', 1));

        $this->assertSame(true, $Redis->lset('list', 1, '15'));
        $this->assertSame(['10', '15', '20', '30', '50', '40', '50', '20', '10', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->lset('list', 4, '35'));
        $this->assertSame(['10', '15', '20', '30', '35', '40', '50', '20', '10', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->lset('list', -1, '100'));
        $this->assertSame(['10', '15', '20', '30', '35', '40', '50', '20', '10', '100'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->lset('list', -3, '60'));
        $this->assertSame(['10', '15', '20', '30', '35', '40', '50', '60', '10', '100'], $Redis->lrange('list', 0, -1));

        try {
            $this->assertSame(true, $Redis->lset('list', 20, 'bar'));
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR index out of range', $Ex->getMessage());
        }

        $Redis->set('foo', 'bar');
        try {
            $Redis->lrem('foo', 0, 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }


    public function test_ltrim() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 10));
        $this->assertSame(2, $Redis->rpush('list', 50));
        $this->assertSame(3, $Redis->rpush('list', 20));
        $this->assertSame(4, $Redis->rpush('list', 30));
        $this->assertSame(5, $Redis->rpush('list', 50));
        $this->assertSame(6, $Redis->rpush('list', 40));
        $this->assertSame(7, $Redis->rpush('list', 50));
        $this->assertSame(8, $Redis->rpush('list', 20));
        $this->assertSame(9, $Redis->rpush('list', 10));
        $this->assertSame(10, $Redis->rpush('list', 1));

        $this->assertSame(true, $Redis->ltrim('list', 0, -1));
        $this->assertSame(['10', '50', '20', '30', '50', '40', '50', '20', '10', '1'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->ltrim('list', 1, -2));
        $this->assertSame(['50', '20', '30', '50', '40', '50', '20', '10'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->ltrim('list', 1, -2));
        $this->assertSame(['20', '30', '50', '40', '50', '20'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->ltrim('list', -5, -1));
        $this->assertSame(['30', '50', '40', '50', '20'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->ltrim('list', 1, 3));
        $this->assertSame(['50', '40', '50'], $Redis->lrange('list', 0, -1));

        $this->assertSame(true, $Redis->ltrim('list', 10, 15));
        $this->assertSame([], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->ltrim('foo', 0, 10);
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_rpop() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(2, $Redis->rpush('list', 200));
        $this->assertSame(3, $Redis->rpush('list', 300));
        $this->assertSame(['100', '200', '300'], $Redis->lrange('list', 0, -1));

        $this->assertSame('300', $Redis->rpop('list'));
        $this->assertSame('200', $Redis->rpop('list'));
        $this->assertSame(['100'], $Redis->lrange('list', 0, -1));
        $this->assertSame('100', $Redis->rpop('list'));
        $this->assertSame([], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->rpop('foo');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_rpoplpush() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list1', 10));
        $this->assertSame(2, $Redis->rpush('list1', 11));
        $this->assertSame(3, $Redis->rpush('list1', 12));

        $this->assertSame(1, $Redis->rpush('list2', 20));
        $this->assertSame(2, $Redis->rpush('list2', 21));
        $this->assertSame(3, $Redis->rpush('list2', 22));

        $this->assertSame('12', $Redis->rpoplpush('list1', 'list2'));
        $this->assertSame('11', $Redis->rpoplpush('list1', 'list2'));
        $this->assertSame('10', $Redis->rpoplpush('list1', 'list2'));
        $this->assertSame('22', $Redis->rpoplpush('list2', 'list1'));

        $this->assertSame(null, $Redis->rpoplpush('list3', 'list4'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->rpoplpush('foo', 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
        try {
            $Redis->rpoplpush('list2', 'foo');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_rpush() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(2, $Redis->rpush('list', 200));
        $this->assertSame(['100', '200'], $Redis->lrange('list', 0, -1));
        $this->assertSame(3, $Redis->rpush('list', 300));
        $this->assertSame(['100', '200', '300'], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->rpush('foo', 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

    public function test_rpushx() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->rpushx('list', 100));
        $this->assertSame(1, $Redis->rpush('list', 100));
        $this->assertSame(2, $Redis->rpushx('list', 200));
        $this->assertSame(['100', '200'], $Redis->lrange('list', 0, -1));
        $this->assertSame(3, $Redis->rpushx('list', 300));
        $this->assertSame(['100', '200', '300'], $Redis->lrange('list', 0, -1));

        $Redis->set('foo', 'bar');
        try {
            $Redis->rpushx('foo', 'bar');
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame(static::REDIS_RESPONSE_ERROR_WRONGTYPE, $Ex->getMessage());
        }
    }

}
