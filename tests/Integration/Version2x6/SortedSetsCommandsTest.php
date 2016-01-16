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
 * @see SortedSetsCommandsTrait
 */
class SortedSetsCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass() {
        static::$Redis->flushall();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
    }

    public function test_zadd() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('zset', ['a' => 0, 'b' => 2, 'c' => 3]));
        $list = $Redis->zrange('zset', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '0', 'b' => '2', 'c' => '3'], $list);

        $this->assertSame(0, $Redis->zadd('zset', ['a' => 0, 'b' => 2, 'c' => 3]));

        $this->assertSame(0, $Redis->zadd('zset', ['a' => 1, 'b' => 2, 'c' => 3]));
        $list = $Redis->zrange('zset', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '1', 'b' => '2', 'c' => '3'], $list);

        $this->assertSame(2, $Redis->zadd('bar', ['-inf' => '-inf', '+inf' => '+inf']));
        $list = $Redis->zrange('bar', 0, -1, true); ksort($list);
        $this->assertSame(['+inf' => 'inf', '-inf' => '-inf'], $list);

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 1, 'b' => 2, 'c' => 3]));

        $Redis->set('string', 'value');
        try {
            $Redis->zadd('string', ['a' => 0, 'b' => 2, 'c' => 3]);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zcard() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('zset', ['a' => 0, 'b' => 2, 'c' => 3]));
        $this->assertSame(3, $Redis->zcard('zset'));

        $this->assertSame(1, $Redis->zadd('zset', ['d' => 4]));
        $this->assertSame(4, $Redis->zcard('zset'));

        $this->assertSame(0, $Redis->zcard('foo'));

        $Redis->set('string', 'value');
        try {
            $Redis->zadd('string', ['a' => 0, 'b' => 2, 'c' => 3]);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zcount() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('zset', ['a' => 1, 'b' => 2, 'c' => 3]));
        $this->assertSame(0, $Redis->zcount('zset', 0, 0));
        $this->assertSame(1, $Redis->zcount('zset', 1, 1));
        $this->assertSame(2, $Redis->zcount('zset', 2, 5));
        $this->assertSame(3, $Redis->zcount('zset', '-inf', '+inf'));

        $this->assertSame(0, $Redis->zcount('foo', 0, 10));

        $Redis->set('string', 'value');
        try {
            $Redis->zcount('string', 0, 1);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zincrby() {
        $Redis = static::$Redis;

        $this->assertSame('0', $Redis->zincrby('foo', 0, 'a'));
        $this->assertSame('1', $Redis->zincrby('foo', 1, 'a'));
        $this->assertSame('2.5', $Redis->zincrby('foo', 1.5, 'a'));
        $this->assertSame('1', $Redis->zincrby('foo', '-1.5', 'a'));
        $this->assertSame('-inf', $Redis->zincrby('foo', '-inf', 'a'));

        $this->assertSame('-inf', $Redis->zincrby('bar', '-inf', 'a'));

        $Redis->set('string', 'value');
        try {
            $Redis->zincrby('string', 0, 'a');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zinterstore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 1, 'b' => 2, 'c' => 3]));
        $this->assertSame(3, $Redis->zadd('bar', ['b' => 4, 'c' => 5, 'e' => 6]));

        $this->assertSame(2, $Redis->zinterstore('store', ['foo', 'bar']));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['b' => '6', 'c' => '8'], $list);

        $this->assertSame(2, $Redis->zinterstore('store', ['foo', 'bar'], null, 'MIN'));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['b' => '2', 'c' => '3'], $list);

        $this->assertSame(2, $Redis->zinterstore('store', ['bar', 'foo'], null, 'MAX'));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['b' => '4', 'c' => '5'], $list);

        $this->assertSame(2, $Redis->zinterstore('store', ['bar', 'foo'], [5, 1]));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['b' => '22', 'c' => '28'], $list);

        $this->assertSame(3, $Redis->zinterstore('store', 'foo', 2));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '2', 'b' => '4', 'c' => '6'], $list);

        $this->assertSame(0, $Redis->zinterstore('store', ['key']));
        $this->assertSame([], $Redis->zrange('store', 0, -1, true));

        $Redis->set('string', 'value');
        try {
            $Redis->zinterstore('store', ['string' , 'foo']);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrange() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));
        $this->assertSame(['d', 'e', 'f'], $Redis->zrange('foo', -3, -1));
        $this->assertSame(['b' => '20.5', 'c' => '30'], $Redis->zrange('foo', 1, 2, true));
        $this->assertSame(['a' => '10', 'b' => '20.5', 'c' => '30', 'd' => '40', 'e' => '50', 'f' => '60'], $Redis->zrange('foo', 0, -1, true));
        $this->assertSame(['a' => '10'], $Redis->zrange('foo', 0, 0, true));
        $this->assertSame(['f' => '60'], $Redis->zrange('foo', -1, -1, true));

        $this->assertSame([], $Redis->zrange('bar', -1, -1, true));

        $Redis->set('string', 'value');
        try {
            $Redis->zrange('string', 0, -1);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrangebyscore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));

        $this->assertSame(['a', 'b', 'c'], $Redis->zrangebyscore('foo', 0, 30));
        $this->assertSame(['a' => '10', 'b' => '20.5'], $Redis->zrangebyscore('foo', 0, '(30', true));

        $this->assertSame(['b'], $Redis->zrangebyscore('foo', '(10', '(30'));
        $this->assertSame(['b' => '20.5'], $Redis->zrangebyscore('foo', '(10', '(30', true));

        $this->assertSame(['c' => '30'], $Redis->zrangebyscore('foo', '-inf', '+inf', true, [2, 1]));
        $this->assertSame(['a' => '10'], $Redis->zrangebyscore('foo', '-inf', '+inf', true, 1));

        $this->assertSame([], $Redis->zrangebyscore('bar', '-inf', '+inf', true, 1));

        $Redis->set('string', 'value');
        try {
            $Redis->zrangebyscore('string', 10, 30);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrank() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));

        $this->assertSame(0, $Redis->zrank('foo', 'a'));
        $this->assertSame(1, $Redis->zrank('foo', 'b'));
        $this->assertSame(2, $Redis->zrank('foo', 'c'));
        $this->assertSame(3, $Redis->zrank('foo', 'd'));
        $this->assertSame(4, $Redis->zrank('foo', 'e'));
        $this->assertSame(5, $Redis->zrank('foo', 'f'));

        $this->assertSame(null, $Redis->zrank('foo', 'g'));
        $this->assertSame(null, $Redis->zrank('bar', 'g'));

        $Redis->set('string', 'value');
        try {
            $Redis->zrank('string', 'a');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrem() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));
        $this->assertSame(5, $Redis->zrank('foo', 'f'));

        $this->assertSame(5, $Redis->zrank('foo', 'f'));

        $this->assertSame(1, $Redis->zrem('foo', 'a'));
        $this->assertSame(null, $Redis->zrank('bar', 'a'));
        $this->assertSame(4, $Redis->zrank('foo', 'f'));

        $this->assertSame(2, $Redis->zrem('foo', ['a', 'b', 'c']));
        $this->assertSame(null, $Redis->zrank('bar', 'b'));
        $this->assertSame(null, $Redis->zrank('bar', 'c'));
        $this->assertSame(2, $Redis->zrank('foo', 'f'));

        $this->assertSame(2, $Redis->zrem('foo', ['d', 'e', 'e']));
        $this->assertSame(null, $Redis->zrank('bar', 'd'));
        $this->assertSame(null, $Redis->zrank('bar', 'e'));
        $this->assertSame(0, $Redis->zrank('foo', 'f'));

        $this->assertSame(0, $Redis->zrem('bar', ['d', 'e', 'e']));

        $Redis->set('string', 'value');
        try {
            $Redis->zrem('string', 'a');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zremrangebyrank() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20, 'c' => 30]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));
        $this->assertSame(1, $Redis->zremrangebyrank('foo', 0, 0));
        $this->assertSame(['b', 'c', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(1, $Redis->zremrangebyrank('foo', 1, 1));
        $this->assertSame(['b', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(2, $Redis->zremrangebyrank('foo', 1, -2));
        $this->assertSame(['b', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(0, $Redis->zremrangebyrank('bar', 0, -1));

        $Redis->set('string', 'value');
        try {
            $this->assertSame(0, $Redis->zremrangebyrank('string', 0, -1));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zremrangebyscore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20, 'c' => 30]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));
        $this->assertSame(1, $Redis->zremrangebyscore('foo', 0, 10));
        $this->assertSame(['b', 'c', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(1, $Redis->zremrangebyscore('foo', 25, 30));
        $this->assertSame(['b', 'd', 'e', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(2, $Redis->zremrangebyscore('foo', 30, 50));
        $this->assertSame(['b', 'f'], $Redis->zrange('foo', 0, -1));

        $this->assertSame(0, $Redis->zremrangebyscore('bar', 0, 100));

        $Redis->set('string', 'value');
        try {
            $this->assertSame(0, $Redis->zremrangebyscore('string', 0, 100));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrevrange() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));

        $this->assertSame(['f', 'e', 'd', 'c', 'b', 'a'], $Redis->zrevrange('foo', 0, -1));
        $this->assertSame(['c', 'b', 'a'], $Redis->zrevrange('foo', -3, -1));
        $this->assertSame(['e' => '50', 'd' => '40'], $Redis->zrevrange('foo', 1, 2, true));
        $this->assertSame(['f' => '60', 'e' => '50', 'd' => '40', 'c' => '30', 'b' => '20.5', 'a' => '10'], $Redis->zrevrange('foo', 0, -1, true));
        $this->assertSame(['f' => '60'], $Redis->zrevrange('foo', 0, 0, true));
        $this->assertSame(['a' => '10'], $Redis->zrevrange('foo', -1, -1, true));

        $this->assertSame([], $Redis->zrevrange('bar', -1, -1, true));

        $Redis->set('string', 'value');
        try {
            $Redis->zrevrange('string', 0, -1);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrevrangebyscore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));

        $this->assertSame(['c', 'b', 'a'], $Redis->zrevrangebyscore('foo', 30, 0));
        $this->assertSame(['b' => '20.5', 'a' => '10'], $Redis->zrevrangebyscore('foo', '(30', 0, true));

        $this->assertSame(['b'], $Redis->zrevrangebyscore('foo', '(30', '(10'));
        $this->assertSame(['b' => '20.5'], $Redis->zrevrangebyscore('foo', '(30', '(10', true));

        $this->assertSame(['d' => '40'], $Redis->zrevrangebyscore('foo', '+inf', '-inf', true, [2, 1]));
        $this->assertSame(['f' => '60'], $Redis->zrevrangebyscore('foo', '+inf', '-inf', true, 1));

        $this->assertSame([], $Redis->zrevrangebyscore('bar', '+inf', '-inf', true, 1));

        $Redis->set('string', 'value');
        try {
            $Redis->zrevrangebyscore('string', 30, 10);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrevrank() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));

        $this->assertSame(0, $Redis->zrevrank('foo', 'f'));
        $this->assertSame(1, $Redis->zrevrank('foo', 'e'));
        $this->assertSame(2, $Redis->zrevrank('foo', 'd'));
        $this->assertSame(3, $Redis->zrevrank('foo', 'c'));
        $this->assertSame(4, $Redis->zrevrank('foo', 'b'));
        $this->assertSame(5, $Redis->zrevrank('foo', 'a'));

        $this->assertSame(null, $Redis->zrevrank('foo', 'g'));
        $this->assertSame(null, $Redis->zrevrank('bar', 'g'));

        $Redis->set('string', 'value');
        try {
            $Redis->zrevrank('string', 'a');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zscore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 40, 'e' => 50, 'f' => 60]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 10, 'b' => 20.5, 'c' => 30]));

        $this->assertSame('10', $Redis->zscore('foo', 'a'));
        $this->assertSame('20.5', $Redis->zscore('foo', 'b'));
        $this->assertSame('30', $Redis->zscore('foo', 'c'));
        $this->assertSame('40', $Redis->zscore('foo', 'd'));
        $this->assertSame('50', $Redis->zscore('foo', 'e'));
        $this->assertSame('60', $Redis->zscore('foo', 'f'));

        $this->assertSame(null, $Redis->zscore('foo', 'g'));
        $this->assertSame(null, $Redis->zscore('bar', 'g'));

        $Redis->set('string', 'value');
        try {
            $Redis->zscore('string', 'a');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zunionstore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 1, 'b' => 2, 'c' => 3]));
        $this->assertSame(3, $Redis->zadd('bar', ['b' => 4, 'c' => 5, 'e' => 6]));

        $this->assertSame(4, $Redis->zunionstore('store', ['foo', 'bar']));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '1', 'b' => '6', 'c' => '8', 'e' => '6'], $list);

        $this->assertSame(4, $Redis->zunionstore('store', ['foo', 'bar'], null, 'MIN'));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '1', 'b' => '2', 'c' => '3', 'e' => '6'], $list);

        $this->assertSame(4, $Redis->zunionstore('store', ['bar', 'foo'], null, 'MAX'));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '1', 'b' => '4', 'c' => '5', 'e' => '6'], $list);

        $this->assertSame(4, $Redis->zunionstore('store', ['bar', 'foo'], [5, 1]));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '1', 'b' => '22', 'c' => '28', 'e' => '30'], $list);

        $this->assertSame(3, $Redis->zunionstore('store', 'foo', 2));
        $list = $Redis->zrange('store', 0, -1, true); ksort($list);
        $this->assertSame(['a' => '2', 'b' => '4', 'c' => '6'], $list);

        $this->assertSame(0, $Redis->zunionstore('store', ['key']));
        $this->assertSame([], $Redis->zrange('store', 0, -1, true));

        $Redis->set('string', 'value');
        try {
            $Redis->zunionstore('store', ['string' , 'foo']);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
