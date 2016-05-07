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

include_once(__DIR__. '/../Version3x0/ServerCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use Test\Integration\Version3x0\ServerCommandsTest as ServerCommandsTestVersion3x0;

/**
 * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait
 */
class ServerCommandsTest extends ServerCommandsTestVersion3x0 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;

    /** @var  RedisClient3x2 */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait::commandCount
     */
    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(171, $Redis->commandCount());
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait::commandCount
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
            'latency',
            'pfdebug',
            'pfselftest',
            'psync',
            'replconf',
            'restore-asking',
            'script',
        ], $missedCommands);
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait::commandCount
     */
    public function test_debugHelp() {
        $Redis = static::$Redis;

        $help = $Redis->debugHelp();
        $this->assertSame('DEBUG <subcommand> arg arg ... arg. Subcommands:', $help[0]);
    }
}
