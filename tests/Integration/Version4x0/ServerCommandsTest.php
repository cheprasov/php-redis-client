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
namespace Test\Integration\Version4x0;

include_once(__DIR__. '/../Version3x2/ServerCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait
 */
class ServerCommandsTest extends \Test\Integration\Version3x2\ServerCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::flushall
     */
    public function test_flushall() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushall(true));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::flushdb
     */
    public function test_flushdb() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushdb(true));
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\ServerCommandsTrait::commandCount
     */
    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(180, $Redis->commandCount());
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
            'georadius_ro',
            'georadiusbymember_ro',
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
        ], $missedCommands);
    }

}
