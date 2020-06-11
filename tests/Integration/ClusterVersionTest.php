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
namespace Test\Integration;

use PHPUnit\Framework\TestCase;
use RedisClient\Client\AbstractRedisClient;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Client\Version\RedisClient5x0;

class ClusterVersionTest extends TestCase {

    static protected $servers_map = [
        RedisClient3x0::class => [
            TEST_REDIS_SERVER_3x2_CLUSTER_A,
            TEST_REDIS_SERVER_3x2_CLUSTER_B,
            TEST_REDIS_SERVER_3x2_CLUSTER_C
        ],
        RedisClient3x2::class => [
            TEST_REDIS_SERVER_3x2_CLUSTER_A,
            TEST_REDIS_SERVER_3x2_CLUSTER_B,
            TEST_REDIS_SERVER_3x2_CLUSTER_C
        ],
        RedisClient4x0::class => [
            TEST_REDIS_SERVER_3x2_CLUSTER_A,
            TEST_REDIS_SERVER_3x2_CLUSTER_B,
            TEST_REDIS_SERVER_3x2_CLUSTER_C
        ],
        RedisClient5x0::class => [
            TEST_REDIS_SERVER_3x2_CLUSTER_A,
            TEST_REDIS_SERVER_3x2_CLUSTER_B,
            TEST_REDIS_SERVER_3x2_CLUSTER_C
        ],
    ];

    protected static function getServers() {
        return static::$servers_map[static::getRedisClientClass()];
    }

    /**
     * @return null|string
     */
    protected static function getRedisClientClass() {
        if (false === strpos(static::class, '\Version')) {
            return null;
        }
        [$testClass, $testVersion] = array_reverse(explode('\\', static::class));
        $version = str_ireplace(['version'], [''], $testVersion);
        $class = str_replace('RedisClient3x0', 'RedisClient' . $version, RedisClient3x0::class);
        return $class;
    }

    /**
     * @param array $config
     * @return AbstractRedisClient|RedisClient3x0|RedisClient3x2|RedisClient4x0
     */
    protected function createRedisClient(array $config = []) {
        $class = $this->getRedisClientClass();
        return new $class($config);
    }

}
