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

/**
 * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait
 */
class ServerCommandsTest extends \Test\Integration\Version3x0\ServerCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version3x2\ServerCommandsTrait::commandCount
     */
    public function test_commandCount() {
        $Redis = static::$Redis;
        $this->assertSame(174, $Redis->commandCount());
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
            'host:',
            'latency',
            'pfdebug',
            'pfselftest',
            'post',
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
