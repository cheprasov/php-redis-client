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
namespace Test\Integration\Version2x8;

include_once(__DIR__. '/../Version2x6/SortedSetsCommandsTest.php');

use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version2x6\SortedSetsCommandsTest as SortedSetsCommandsTestVersion2x6;

/**
 * @see SortedSetsCommandsTrait
 */
class SortedSetsCommandsTest extends SortedSetsCommandsTestVersion2x6 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x8_1;

    /**
     * @var RedisClient2x8
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x8([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    public function test_zlexcount() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 0, 'b' => 0, 'c' => 0]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 0, 'e' => 0, 'f' => 0]));

        $this->assertSame(6, $Redis->zlexcount('foo', '-', '+'));
        $this->assertSame(3, $Redis->zlexcount('foo', '-', '[c'));
        $this->assertSame(6, $Redis->zlexcount('foo', '[a', '[f'));
        $this->assertSame(4, $Redis->zlexcount('foo', '(a', '(f'));

        $this->assertSame(0, $Redis->zlexcount('bar', '(a', '(f'));

        $Redis->set('string', 'value');
        try {
            $Redis->zlexcount('string', '-', '+');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrangebylex() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 0, 'b' => 0, 'c' => 0]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 0, 'e' => 0, 'f' => 0]));

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrangebylex('foo', '-', '+'));
        $this->assertSame(['a', 'b', 'c'], $Redis->zrangebylex('foo', '-', '[c'));
        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrangebylex('foo', '[a', '[f'));
        $this->assertSame(['b', 'c', 'd', 'e'], $Redis->zrangebylex('foo', '(a', '(f'));

        $this->assertSame(['c'], $Redis->zrangebylex('foo', '-', '+', [2, 1]));
        $this->assertSame(['a', 'b'], $Redis->zrangebylex('foo', '-', '[c', 2));
        $this->assertSame(['c', 'd', 'e'], $Redis->zrangebylex('foo', '[a', '[f', [2, 3]));

        $this->assertSame([], $Redis->zrangebylex('bar', '[a', '[f', [2, 3]));

        $Redis->set('string', 'value');
        try {
            $Redis->zrangebylex('string', '-', '+');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zremrangebylex() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 0, 'b' => 0, 'c' => 0]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 0, 'e' => 0, 'f' => 0]));

        $this->assertSame(['a', 'b', 'c', 'd', 'e', 'f'], $Redis->zrangebylex('foo', '-', '+'));
        $this->assertSame(1, $Redis->zremrangebylex('foo', '-', '(b'));
        $this->assertSame(['b', 'c', 'd', 'e', 'f'], $Redis->zrangebylex('foo', '-', '+'));

        $this->assertSame(1, $Redis->zremrangebylex('foo', '[c', '[c'));
        $this->assertSame(['b', 'd', 'e', 'f'], $Redis->zrangebylex('foo', '-', '+'));

        $this->assertSame(2, $Redis->zremrangebylex('foo', '(b', '(f'));
        $this->assertSame(['b', 'f'], $Redis->zrangebylex('foo', '-', '+'));

        $this->assertSame(0, $Redis->zremrangebylex('bar', '[a', '[f'));

        $Redis->set('string', 'value');
        try {
            $this->assertSame(0, $Redis->zremrangebylex('string', '[a', '[f'));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zrevrangebylex() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['a' => 0, 'b' => 0, 'c' => 0]));
        $this->assertSame(3, $Redis->zadd('foo', ['d' => 0, 'e' => 0, 'f' => 0]));

        $this->assertSame(['f', 'e', 'd', 'c', 'b', 'a'], $Redis->zrevrangebylex('foo', '+', '-'));
        $this->assertSame(['c', 'b', 'a'], $Redis->zrevrangebylex('foo', '[c', '-'));
        $this->assertSame(['f', 'e', 'd', 'c', 'b', 'a'], $Redis->zrevrangebylex('foo', '[f', '[a'));
        $this->assertSame(['e', 'd', 'c', 'b'], $Redis->zrevrangebylex('foo', '(f', '(a'));

        $this->assertSame(['d'], $Redis->zrevrangebylex('foo', '+', '-', [2, 1]));
        $this->assertSame(['c', 'b'], $Redis->zrevrangebylex('foo', '[c', '-', 2));
        $this->assertSame(['d', 'c', 'b'], $Redis->zrevrangebylex('foo', '[f', '[a', [2, 3]));

        $this->assertSame([], $Redis->zrevrangebylex('bar', '[f', '[a', [2, 3]));

        $Redis->set('string', 'value');
        try {
            $Redis->zrevrangebylex('string', '-', '+');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_zscan() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->zadd('foo', ['d' => 4, 'e' => 5, 'f' => 6]));
        $this->assertSame(3, $Redis->zadd('foo', ['a' => 1, 'b' => 2, 'c' => 3]));

        $this->assertSame(['0',['a', '1', 'b', '2', 'c', '3', 'd', '4', 'e', '5', 'f', '6']], $Redis->zscan('foo', 0));
        $this->assertSame(['0',[]], $Redis->zscan('bar', 0));

        $Redis->set('string', 'value');
        try {
            $Redis->zscan('string', 0);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
