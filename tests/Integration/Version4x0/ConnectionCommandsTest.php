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

include_once(__DIR__. '/../Version3x2/ConnectionCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait
 */
class ConnectionCommandsTest extends \Test\Integration\Version3x2\ConnectionCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait::swapdb
     */
    public function test_swapdb() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->select(0));
        $this->assertSame(true, $Redis->set('foo', 'db0'));
        $this->assertSame(true, $Redis->select(1));
        $this->assertSame(true, $Redis->set('foo', 'db1'));

        $this->assertSame(true, $Redis->select(0));

        $this->assertSame(true, $Redis->swapdb(0, 1));
        $this->assertSame('db1', $Redis->get('foo'));

        $this->assertSame(true, $Redis->swapdb(0, 1));
        $this->assertSame('db0', $Redis->get('foo'));
    }

}
