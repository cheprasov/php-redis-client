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
namespace Test\Integration\Version2x6;

use RedisClient\Exception\ErrorResponseException;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait
 */
class ConnectionCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::auth
     */
    public function test_auth() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->auth('password'));
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR Client sent AUTH, but no password is set', $Ex->getMessage());
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::echoMessage
     */
    public function test_echoMessage() {
        $Redis = static::$Redis;

        $this->assertSame('message', $Redis->echoMessage('message'));
        $this->assertSame('', $Redis->echoMessage(''));
        $this->assertSame('foo bar', $Redis->echoMessage('foo bar'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::echoMessage
     */
    public function test_echo() {
        $Redis = static::$Redis;

        $this->assertSame('message', $Redis->echo('message'));
        $this->assertSame('', $Redis->echo(''));
        $this->assertSame('foo bar', $Redis->echo('foo bar'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::ping
     */
    public function test_ping() {
        $Redis = static::$Redis;
        $this->assertSame('PONG', $Redis->ping());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::quit
     */
    /*
    public function test_quit() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->quit());
    }
    */

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::select
     */
    public function test_select() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('foo', 'db 0'));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(1));
        $this->assertSame(true, $Redis->set('foo', 'db 1'));
        $this->assertSame('db 1', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(0));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(2));
        $this->assertSame(null, $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(0));
        $this->assertSame('db 0', $Redis->get('foo'));

        $this->assertSame(true, $Redis->select(1));
        $this->assertSame('db 1', $Redis->get('foo'));
    }

}
