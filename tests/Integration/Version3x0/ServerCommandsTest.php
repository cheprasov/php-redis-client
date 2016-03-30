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
namespace Test\Integration\Version3x0;

include_once(__DIR__. '/../Version2x8/ServerCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x0;
use Test\Integration\Version2x8\ServerCommandsTest as ServerCommandsTestVersion2x8;

/**
 * @see \RedisClient\Command\Traits\Version2x8\ServerCommandsTrait
 */
class ServerCommandsTest extends ServerCommandsTestVersion2x8 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x0_1;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(163, $Redis->commandCount());
    }

    public function test_command() {
        $Redis = static::$Redis;

        $this->assertSame(true, is_array($commands = $Redis->command()));
        $skip = [
            'script', 'eval', 'echo',
            'latency', 'config', 'client',
            'pfdebug', 'pfselftest', 'replconf',
            'debug' ,'psync', 'cluster',
            'asking', 'restore-asking'
        ];
        foreach ($commands as $command) {
            if (in_array($command[0], $skip)) {
                continue;
            }
            $this->assertSame(true, method_exists($Redis, $command[0]) ?: $command);
        }
    }
}
