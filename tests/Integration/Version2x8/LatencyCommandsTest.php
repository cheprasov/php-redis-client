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
namespace Test\Integration\Version2x8;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x8\LatencyCommandsTrait
 */
class LatencyCommandsTest extends \Test\Integration\BaseVersionTest {

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
