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
namespace Test\Integration\Version4x0;

include_once(__DIR__. '/../Version3x2/HyperLogLogCommandsTest.php');

use RedisClient\Client\Version\RedisClient4x0;
use Test\Integration\Version3x2\HyperLogLogCommandsTest as HyperLogLogCommandsTestVersion3x2;

/**
 * @see \RedisClient\Command\Traits\Version2x8\HyperLogLogCommandsTrait
 */
class HyperLogLogCommandsTest extends HyperLogLogCommandsTestVersion3x2 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_4x0_1;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

}
