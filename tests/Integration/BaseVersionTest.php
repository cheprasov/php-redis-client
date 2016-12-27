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

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Client\Version\RedisClient4x0;

class BaseVersionTest extends \PHPUnit_Framework_TestCase {

    const SERVER_1 = 0;
    const SERVER_2 = 1;

    /**
     * @var AbstractRedisClient|RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient4x0
     */
    static protected $Redis;

    /**
     * @var AbstractRedisClient|RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient4x0
     */
    static protected $Redis2;

    static protected $servers_map = [
        RedisClient2x6::class => [TEST_REDIS_SERVER_2x6_1, TEST_REDIS_SERVER_2x6_2],
        RedisClient2x8::class => [TEST_REDIS_SERVER_2x8_1, TEST_REDIS_SERVER_2x8_2],
        RedisClient3x0::class => [TEST_REDIS_SERVER_3x0_1, TEST_REDIS_SERVER_3x0_2],
        RedisClient3x2::class => [TEST_REDIS_SERVER_3x2_1, TEST_REDIS_SERVER_3x2_2],
        RedisClient4x0::class => [TEST_REDIS_SERVER_4x0_1, TEST_REDIS_SERVER_4x0_2],
    ];

    static protected $test_config_map = [
        'default' => [
            [
                'timeout' => 2,
            ],
            [
                'timeout' => 2,
                'password' => TEST_REDIS_SERVER_PASSWORD,
            ],
        ],
        'ListsCommandsTest' => [
            [
                'timeout' => 10,
            ],
            [
                'timeout' => 10,
                'password' => TEST_REDIS_SERVER_PASSWORD,
            ],
        ],
    ];

    /**
     * @return array
     */
    protected static function getTestConfig() {
        if (false === strpos(static::class, '\Version')) {
            return null;
        }
        list($testClass, $testVersion) = array_reverse(explode('\\', static::class));
        $version = str_ireplace(['version'], [''], $testVersion);
        $class = str_replace('RedisClient2x6', 'RedisClient' . $version, RedisClient2x6::class);
        $servers = self::$servers_map[$class];

        $configs = isset(static::$test_config_map[$testClass])
            ? static::$test_config_map[$testClass]
            : static::$test_config_map['default'];

        foreach ($servers as $key => $server) {
            $configs[$key]['server'] = $server;
        }

        return [
            'class'   => $class,
            'version' => $version,
            'servers' => $servers,
            'configs' => $configs
        ];
    }

    /**
     * @return null|AbstractRedisClient|RedisClient2x6|RedisClient2x8|RedisClient3x0|RedisClient3x2|RedisClient4x0
     */
    protected static function getRedisClient($serverId) {
        $testConfig = static::getTestConfig();
        if (!$testConfig) {
            return null;
        }
        $config = $testConfig['configs'][$serverId];
        $class = $testConfig['class'];
        return new $class($config);
    }

    public static function setUpBeforeClass() {
        static::$Redis  = self::getRedisClient(self::SERVER_1);
        static::$Redis2 = self::getRedisClient(self::SERVER_2);
    }

    public static function tearDownAfterClass() {
        if (static::$Redis) {
            static::$Redis->select(0);
            static::$Redis->flushall();
            static::$Redis->scriptFlush();
        }
        if (static::$Redis2) {
            static::$Redis2->select(0);
            static::$Redis2->flushall();
            static::$Redis2->scriptFlush();
        }
    }

    protected function setUp() {
        static::tearDownAfterClass();
    }

    public function testSetup() {
        if (!static::$Redis) {
            $this->markTestSkipped();
        }
        $this->assertTrue(static::$Redis instanceof AbstractRedisClient, 'Can not create RedisClient for test');
    }

}
