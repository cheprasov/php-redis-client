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

include_once(__DIR__. '/../Version2x6/ServerCommandsTest.php');

use RedisClient\Client\Version\RedisClient2x8;
use Test\Integration\Version2x6\ServerCommandsTest as ServerCommandsTestVersion2x6;

/**
 * @see ServerCommandsTrait
 */
class ServerCommandsTest extends ServerCommandsTestVersion2x6 {

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

    public function test_command() {
        $Redis = static::$Redis;

        $this->assertSame(true, is_array($commands = $Redis->command()));
        $skip = [
            'script', 'eval', 'echo',
            'latency', 'config', 'client',
            'pfdebug', 'pfselftest', 'replconf',
            'debug' ,'psync'
        ];
        foreach ($commands as $command) {
            if (in_array($command[0], $skip)) {
                continue;
            }
            $this->assertSame(true, method_exists($Redis, $command[0]) ?: $command);
        }
    }

    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(157, $Redis->commandCount());
    }

    public function _test_commandGetkeys() {
        // todo: send report about issue
        $Redis = static::$Redis;
        $this->assertSame(['a', 'c', 'e'], $Redis->commandGetkeys('MSET a b c d e f'));
    }

    public function test_commandInfo() {
        $Redis = static::$Redis;
        $this->assertSame([null], $Redis->commandInfo('foo'));
        $this->assertSame([['get', 2, ['readonly', 'fast'], 1, 1, 1]], $Redis->commandInfo('get'));
        $this->assertSame([['set', -3, ['write', 'denyoom'], 1, 1, 1]], $Redis->commandInfo('set'));
        $this->assertSame([['eval', -3, ['noscript', 'movablekeys'], 0, 0, 0]], $Redis->commandInfo('eval'));
        $this->assertSame([
            ['get', 2, ['readonly', 'fast'], 1, 1, 1],
            ['set', -3, ['write', 'denyoom'], 1, 1, 1],
            ['eval', -3, ['noscript', 'movablekeys'], 0, 0, 0]
        ], $Redis->commandInfo(['get', 'set', 'eval']));
    }

    public function test_role() {
        $Redis = static::$Redis;
        $this->assertSame(['master', 0, []], $Redis->role());
    }
}
