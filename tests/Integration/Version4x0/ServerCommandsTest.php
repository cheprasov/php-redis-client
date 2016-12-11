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

include_once(__DIR__. '/../Version3x2/ServerCommandsTest.php');

use RedisClient\Client\Version\RedisClient4x0;
use Test\Integration\Version3x2\ServerCommandsTest as ServerCommandsTestVersion3x2;

/**
 * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait
 */
class ServerCommandsTest extends ServerCommandsTestVersion3x2 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_4x0_1;

    /** @var  RedisClient4x0 */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait::commandCount
     */
    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(178, $Redis->commandCount());
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait::commandCount
     */
    public function test_command() {
        $Redis = static::$Redis;

        $commands = $Redis->command();
        $missedCommands = [];
        foreach ($commands as $command) {
            if (method_exists($Redis, $command[0])) {
                continue;
            }
            $missedCommands[] = $command[0];
        }
        sort($missedCommands);

        $this->assertSame([
            'asking',
            'client',
            'cluster',
            'config',
            'debug',
            'echo',
            'eval',
            'host:',
            'latency',
            'memory',
            'module',
            'pfdebug',
            'pfselftest',
            'post',
            'psync',
            'replconf',
            'restore-asking',
            'script',
            'swapdb',
        ], $missedCommands);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait::commandCount
     */
    public function test_debugHelp() {
        $Redis = static::$Redis;

        $help = $Redis->debugHelp();
        $this->assertSame('DEBUG <subcommand> arg arg ... arg. Subcommands:', $help[0]);
    }
}
