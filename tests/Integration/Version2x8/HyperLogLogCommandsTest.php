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

use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Exception\ErrorResponseException;

/**
 * @see HyperLogLogCommandsTrait
 */
class HyperLogLogCommandsTest extends \PHPUnit_Framework_TestCase {

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

    /**
     * @inheritdoc
     */
    public function setUp() {
        static::$Redis->flushall();
    }

    /**
     * @see HyperLogLogCommandsTrait::pfadd
     */
    public function test_pfadd() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->pfadd('hll', ['a', 'b', 'c', 'd', 'e', 'f' , 'g']));
        $this->assertSame(7, $Redis->pfcount('hll'));

        $this->assertSame(1, $Redis->pfadd('hll', ['f' , 'g', 'h', 'i']));
        $this->assertSame(9, $Redis->pfcount('hll'));

        $this->assertSame(0, $Redis->pfadd('hll', 'a'));
        $this->assertSame(9, $Redis->pfcount('hll'));

        $this->assertSame(0, $Redis->pfadd('hll', ['a', 'b', 'c']));
        $this->assertSame(9, $Redis->pfcount('hll'));

        $this->assertSame(true, is_string($Redis->get('hll')));
        $this->assertSame('HYLL', substr($Redis->get('hll'), 0, 4));

        $this->assertSame(1, $Redis->hset('foo', 'bar', '42'));
        try {
            $this->assertSame(0, $Redis->pfadd('foo', ['a', 'b', 'c']));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see HyperLogLogCommandsTrait::pfcount
     */
    public function test_pfcount() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->pfcount('hll'));

        $this->assertSame(1, $Redis->pfadd('hll', ['a', 'b', 'c', 'd', 'e', 'f' , 'g']));
        $this->assertSame(7, $Redis->pfcount('hll'));

        $this->assertSame(1, $Redis->pfadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->pfcount('bar'));

        $this->assertSame(7, $Redis->pfcount(['hll', 'bar']));
        $this->assertSame(7, $Redis->pfcount(['hll', 'bar', 'foo']));
        $this->assertSame(3, $Redis->pfcount(['bar', 'bar', 'bar']));

        $this->assertSame(1, $Redis->hset('foo', 'bar', '42'));
        try {
            $this->assertSame(0, $Redis->pfcount('foo'));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see HyperLogLogCommandsTrait::pfmerge
     */
    public function test_pfmerge() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->pfcount('hll'));

        $this->assertSame(1, $Redis->pfadd('foo', ['a', 'b', 'c', 'd']));
        $this->assertSame(1, $Redis->pfadd('bar', ['d', 'e', 'f' , 'g']));
        $this->assertSame(7, $Redis->pfcount(['foo', 'bar']));

        $this->assertSame(true, $Redis->pfmerge('hll', ['foo', 'bar', 'unknown']));
        $this->assertSame(7, $Redis->pfcount('hll'));

        $this->assertSame(true, $Redis->pfmerge('hll', 'foo'));
        $this->assertSame(7, $Redis->pfcount('hll'));

        $this->assertSame(0, $Redis->pfadd('foo', 'a'));
        $this->assertSame(0, $Redis->pfadd('foo', 'b'));
        $this->assertSame(0, $Redis->pfadd('foo', 'c'));
        $this->assertSame(0, $Redis->pfadd('foo', 'd'));

        $this->assertSame(true, $Redis->pfmerge('hll', 'hll'));
        $this->assertSame(7, $Redis->pfcount('hll'));

        $this->assertSame(1, $Redis->hset('poo', 'bar', '42'));
        try {
            $Redis->pfmerge('hll', 'poo');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
