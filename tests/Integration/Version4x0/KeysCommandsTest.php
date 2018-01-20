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

include_once(__DIR__. '/../Version3x2/KeysCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version4x0\KeysCommandsTrait
 */
class KeysCommandsTest extends \Test\Integration\Version3x2\KeysCommandsTest {

    public function test_dump() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->dump('key'));

        $Redis->set('key', "\x00");
        $this->assertSame("\x0\x1\x0\x8\x0\xE2\xD5\xC9\x73\xC5\xE\xC9\x6E", $Redis->dump('key'));

        $Redis->set('key', "1");
        $this->assertSame("\x0\xC0\x1\x8\x0\x9F\x55\xB\x9\x78\x18\x9B\xC4", $Redis->dump('key'));

        $Redis->set('key', "10");
        $this->assertSame("\x0\xC0\xA\x8\x0\xD7\xB2\xBB\xFA\xA7\xB7\xE9\x83", $Redis->dump('key'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame("\xD\x19\x19\x0\x0\x0\x11\x0\x0\x0\x2\x0\x0\x5\x66\x69\x65\x6C\x64\x7\x5\x76\x61\x6C\x75\x65\xFF\x8\x0\xD5\xCE\xF7\x1\x76\x45\x5F\xF2", $Redis->dump('hash'));
    }

    public function test_restore() {
        parent::test_restore();
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\x1\x0\x8\x0\xE2\xD5\xC9\x73\xC5\xE\xC9\x6E", true));
        $this->assertSame("\x00", $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\xC0\x1\x8\x0\x9F\x55\xB\x9\x78\x18\x9B\xC4", true));
        $this->assertSame('1', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\xC0\xA\x8\x0\xD7\xB2\xBB\xFA\xA7\xB7\xE9\x83", true));
        $this->assertSame('10', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('hash', 0, "\xD\x19\x19\x0\x0\x0\x11\x0\x0\x0\x2\x0\x0\x5\x66\x69\x65\x6C\x64\x7\x5\x76\x61\x6C\x75\x65\xFF\x8\x0\xD5\xCE\xF7\x1\x76\x45\x5F\xF2", true));
        $this->assertSame('value', $Redis->hget('hash', 'field'));
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
