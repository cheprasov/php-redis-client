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

/**
 * @see \RedisClient\Command\Traits\Version2x8\LatencyCommandsTrait
 */
class LatencyCommandsTest extends \PHPUnit_Framework_TestCase {

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
     * @see \RedisClient\Command\Traits\Version2x8\LatencyCommandsTrait::latencyLatest
     */
    public function test_latencyLatest() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->latencyLatest());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x8\LatencyCommandsTrait::latencyReset
     */
    public function test_latencyReset() {
        $Redis = static::$Redis;

        $this->assertTrue(is_int($Redis->latencyReset()));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x8\LatencyCommandsTrait::latencyDoctor
     */
    public function test_latencyDoctor() {
        $Redis = static::$Redis;

        $this->assertTrue(is_string($Redis->latencyDoctor()));
    }

}
