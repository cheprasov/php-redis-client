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
namespace Test\Integration\Version2x8;

include_once(__DIR__. '/../Version2x6/KeysCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x8\KeysCommandsTrait
 */
class KeysCommandsTest extends \Test\Integration\Version2x6\KeysCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\KeysCommandsTrait::ttl
     */
    public function test_ttl() {
        $Redis = static::$Redis;

        $this->assertSame(-2, $Redis->ttl('key'));
        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->ttl('key'));
        $Redis->expire('key', 10);
        $this->assertGreaterThanOrEqual(9, $Redis->ttl('key'));
        $this->assertLessThanOrEqual(10, $Redis->ttl('key'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\KeysCommandsTrait::pttl
     */
    public function test_pttl() {
        $Redis = static::$Redis;

        $this->assertSame(-2, $Redis->pttl('key'));
        $Redis->set('key', 'value');
        $this->assertSame(-1, $Redis->pttl('key'));
        $Redis->pexpire('key', 1000);
        $this->assertGreaterThanOrEqual(995, $Redis->pttl('key'));
        $this->assertLessThanOrEqual(1000, $Redis->pttl('key'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x8\KeysCommandsTrait::scan
     */
    public function test_scan() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->mset(['key' => 'value', 'foo' => 'bar', 'hello' => 'world']));
        $result = $Redis->scan(0);
        sort($result[1]);
        $this->assertSame(['0', ['foo', 'hello', 'key']], $result);
    }
}
