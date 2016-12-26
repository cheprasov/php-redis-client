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

include_once(__DIR__. '/../Version3x2/KeysCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version4x0\KeysCommandsTrait
 */
class KeysCommandsTest extends \Test\Integration\Version3x2\KeysCommandsTest {

    public function test_dump() {
        $this->markTestSkipped();
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\KeysCommandsTrait::unlink
     */
    public function test_unlink() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->set('foo1', 'bar1'));
        $this->assertSame(true, $Redis->set('foo2', 'bar2'));
        $this->assertSame(true, $Redis->set('foo3', 'bar3'));
        $this->assertSame(true, $Redis->set('foo4', 'bar4'));

        $this->assertSame(1, $Redis->unlink('foo1'));
        $this->assertSame(2, $Redis->unlink(['foo1', 'foo2', 'foo3']));
        $this->assertSame(1, $Redis->unlink(['foo4', 'foo4']));
        $this->assertSame(0, $Redis->unlink(['foo1', 'foo2', 'foo3', 'foo4']));
    }
}
