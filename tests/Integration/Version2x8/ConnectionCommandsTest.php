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

include_once(__DIR__. '/../Version2x6/ConnectionCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait
 */
class ConnectionCommandsTest extends \Test\Integration\Version2x6\ConnectionCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait::ping
     */
    public function test_ping() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->ping());
        $this->assertSame('foo', $Redis->ping('foo'));
        $this->assertSame("foo\r\nbar", $Redis->ping("foo\r\nbar"));
        $this->assertSame("", $Redis->ping(""));
    }

}
