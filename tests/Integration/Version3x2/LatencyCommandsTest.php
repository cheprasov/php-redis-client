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

include_once(__DIR__. '/../Version3x0/LatencyCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use Test\Integration\Version3x0\LatencyCommandsTest as LatencyCommandsTestVersion3x0;

/**
 * @see LatencyCommandsTrait
 */
class LatencyCommandsTest extends LatencyCommandsTestVersion3x0 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

}
