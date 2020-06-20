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
namespace Test\Integration\Version6x0;

include_once(__DIR__. '/../Version5x0/ServerCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait
 */
class ServerCommandsTest extends \Test\Integration\Version5x0\ServerCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version5x0\ServerCommandsTrait::commandCount
     */
    public function test_commandCount() {
        $Redis = static::$Redis;

        $this->assertSame(204, $Redis->commandCount());
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\ServerCommandsTrait::commandCount
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
            'acl', // new
            'asking',
            'bitfield_ro', // new
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
            'stralgo', // new
            'xgroup',
        ], $missedCommands);
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x8\ServerCommandsTrait::commandInfo
     */
    public function test_commandInfo() {
        $Redis = static::$Redis;
        $this->assertSame([null], $Redis->commandInfo('foo'));
        $this->assertSame([['get', 2, ['readonly', 'fast'], 1, 1, 1, ['@read', '@string', '@fast']]], $Redis->commandInfo('get'));
        $this->assertSame([['set', -3, ['write', 'denyoom'], 1, 1, 1, ['@write', '@string', '@slow']]], $Redis->commandInfo('set'));
        $this->assertSame([['eval', -3, ['noscript', 'movablekeys'], 0, 0, 0, ['@slow', '@scripting']]], $Redis->commandInfo('eval'));
        $this->assertSame([
            ['get', 2, ['readonly', 'fast'], 1, 1, 1, ['@read', '@string', '@fast']],
            ['set', -3, ['write', 'denyoom'], 1, 1, 1, ['@write', '@string', '@slow']],
            ['eval', -3, ['noscript', 'movablekeys'], 0, 0, 0, ['@slow', '@scripting']]
        ], $Redis->commandInfo(['get', 'set', 'eval']));
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclCat()
     */
    public function test_aclCat() {
        $Redis = static::$Redis;

        $this->assertSame(
            [
                'keyspace', 'read', 'write', 'set', 'sortedset', 'list', 'hash', 'string',
                'bitmap', 'hyperloglog', 'geo', 'stream', 'pubsub', 'admin', 'fast', 'slow',
                'blocking', 'dangerous', 'connection', 'transaction', 'scripting'
            ],
            $Redis->aclCat()
        );
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclGenpass()
     */
    public function test_aclGenpass() {
        $Redis = static::$Redis;

        $this->assertSame(1, preg_match('/^\w{64}$/', $Redis->aclGenpass()));
        $this->assertSame(1, preg_match('/^\w{8}$/', $Redis->aclGenpass(32)));
        $this->assertSame(1, preg_match('/^\w{2}$/', $Redis->aclGenpass(5)));
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclGetuser()
     */
    public function test_aclGetuser() {
        $Redis = static::$Redis;

        $this->assertSame(
            [
                'flags',
                ['on', 'allkeys', 'allcommands', 'nopass'],
                'passwords',
                [],
                'commands',
                '+@all',
                'keys',
                ['*'],
            ],
            $Redis->aclGetuser('default')
        );
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclList()
     */
    public function test_aclList() {
        $Redis = static::$Redis;
        $this->assertSame(['user default on nopass ~* +@all'], $Redis->aclList());
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclUsers()
     */
    public function test_aclUsers() {
        $Redis = static::$Redis;
        $this->assertSame(['default'], $Redis->aclUsers());
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ServerCommandsTrait::aclWhoami()
     */
    public function test_aclWhoami() {
        $Redis = static::$Redis;
        $this->assertSame('default', $Redis->aclWhoami());
    }

}
