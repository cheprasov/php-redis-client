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

use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x2\KeysCommandsTest as KeysCommandsTestVersion3x2;

/**
 * @see \RedisClient\Command\Traits\Version4x0\KeysCommandsTrait
 */
class KeysCommandsTest extends KeysCommandsTestVersion3x2 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_4x0_1;
    const TEST_REDIS_SERVER_2 = TEST_REDIS_SERVER_4x0_2;

    /**
     * @var RedisClient4x0
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
        static::$Redis2 = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_2,
            'timeout' => 2,
            'password' => TEST_REDIS_SERVER_PASSWORD,
        ]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\KeysCommandsTrait::dump
     */
    public function test_dump() {
        $this->markTestSkipped();
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->dump('key'));

        $Redis->set('key', "\x00");
        $this->assertSame("\x00\x01\x00\x07\x00\xa4\xca\xf0\x3f\x64\xdd\x96\x4c", $Redis->dump('key'));

        $Redis->set('key', "1");
        $this->assertSame("\x00\xc0\x01\x07\x00\xd9\x4a\x32\x45\xd9\xcb\xc4\xe6", $Redis->dump('key'));

        $Redis->set('key', "10");
        $this->assertSame("\x00\xc0\x0a\x07\x00\x91\xad\x82\xb6\x06\x64\xb6\xa1", $Redis->dump('key'));

        $Redis->hset('hash', 'field', 'value');
        $this->assertSame("\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05\x66\x69\x65\x6c\x64".
            "\x07\x05\x76\x61\x6c\x75\x65\xff\x07\x00\x93\xd1\xce\x4d\xd7\x96\x00\xd0", $Redis->dump('hash'));

    }

}
