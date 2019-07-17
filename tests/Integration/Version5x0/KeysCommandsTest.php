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
namespace Test\Integration\Version5x0;

include_once(__DIR__. '/../Version4x0/KeysCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version5x0\KeysCommandsTrait
 */
class KeysCommandsTest extends \Test\Integration\Version4x0\KeysCommandsTest {

    public function test_dump() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->dump('key'));

        $Redis->set('key', "\x00");
        $this->assertSame("\x0\x1\x0\x9\x0\x8B\xA\x74\x0\x38\x91\x20\xE7", $Redis->dump('key'));

        $Redis->set('key', "1");
        $this->assertSame("\x0\xC0\x1\x9\x0\xF6\x8A\xB6\x7A\x85\x87\x72\x4D", $Redis->dump('key'));

        $Redis->set('key', "10");
        $this->assertSame("\x0\xC0\xA\x9\x0\xBE\x6D\x6\x89\x5A\x28\x0\xA", $Redis->dump('key'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame("\xD\x19\x19\x0\x0\x0\x11\x0\x0\x0\x2\x0\x0\x5\x66\x69\x65\x6C\x64\x7\x5\x76\x61\x6C\x75\x65\xFF\x9\x0\xBC\x11\x4A\x72\x8B\xDA\xB6\x7B", $Redis->dump('hash'));
    }

    public function test_restore() {
        parent::test_restore();
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\x1\x0\x9\x0\x8B\xA\x74\x0\x38\x91\x20\xE7", true));
        $this->assertSame("\x00", $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\xC0\x1\x9\x0\xF6\x8A\xB6\x7A\x85\x87\x72\x4D", true));
        $this->assertSame('1', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x0\xC0\xA\x9\x0\xBE\x6D\x6\x89\x5A\x28\x0\xA", true));
        $this->assertSame('10', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('hash', 0, "\xD\x19\x19\x0\x0\x0\x11\x0\x0\x0\x2\x0\x0\x5\x66\x69\x65\x6C\x64\x7\x5\x76\x61\x6C\x75\x65\xFF\x9\x0\xBC\x11\x4A\x72\x8B\xDA\xB6\x7B", true));
        $this->assertSame('value', $Redis->hget('hash', 'field'));
    }
}
