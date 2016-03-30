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
namespace Test\Integration\Version3x0;

include_once(__DIR__. '/../Version2x8/KeysCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version2x8\KeysCommandsTest as KeysCommandsTestVersion2x8;

/**
 * @see \RedisClient\Command\Traits\Version3x0\KeysCommandsTrait
 */
class KeysCommandsTest extends KeysCommandsTestVersion2x8 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x0_1;
    const TEST_REDIS_SERVER_2 = TEST_REDIS_SERVER_3x0_2;

    /**
     * @var RedisClient3x0
     */
    protected static $Redis;

    /**
     * @var RedisClient3x0
     */
    protected static $Redis2;

    /**
     * @var array
     */
    protected static $fields;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
        static::$Redis2 = new RedisClient3x0([
            'server' =>  static::TEST_REDIS_SERVER_2,
            'timeout' => 2,
        ]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\KeysCommandsTrait::exists
     */
    public function test_exists() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->exists('key'));

        $Redis->set('key', 'value');
        $this->assertSame(1, $Redis->exists('key'));

        $Redis->del('key');
        $this->assertSame(0, $Redis->exists('key'));

        $Redis->set('key', 'value');
        $this->assertSame(3, $Redis->exists(['key', 'key', 'key']));
        $this->assertSame(3, $Redis->exists(['key', 'key', 'key', 'key1', 'key2']));

        $Redis->set('key1', 'value');
        $this->assertSame(4, $Redis->exists(['key', 'key', 'key', 'key1', 'key2']));
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\KeysCommandsTrait::migrate
     */
    public function test_migrate() {
        $Redis = static::$Redis;
        $Redis2 = static::$Redis2;
        $this->assertSame(true, $Redis2->flushall());

        list(, $host, $port) = explode(':', str_replace('/', '', static::TEST_REDIS_SERVER_2), 3);

        $this->assertSame(true, $Redis->set('one', 1));

        $this->assertSame(null, $Redis2->get('one'));
        $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, true));
        $this->assertSame('1', $Redis2->get('one'));
        $this->assertSame('1', $Redis->get('one'));

        $this->assertSame(true, $Redis->set('one', 11));

        try {
            $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, true));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(true, $Redis->migrate($host, $port, 'one', 0, 100, false, true));
        $this->assertSame('11', $Redis2->get('one'));
        $this->assertSame(null, $Redis->get('one'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\KeysCommandsTrait::restore
     */
    public function test_restore() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\x01\x00\x06\x00\xcd\x15\x4d\x4c\x99\x42\x7f\xc5"));
        $this->assertSame("\x00", $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\xc0\x01\x06\x00\xb0\x95\x8f6\$T-o", true));
        $this->assertSame('1', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('key', 0, "\x00\xc0\n\x06\x00\xf8r?\xc5\xfb\xfb_(", true));
        $this->assertSame('10', $Redis->get('key'));

        $this->assertSame(true, $Redis->restore('hash', 0, "\x0d\x19\x19\x00\x00\x00\x11\x00\x00\x00\x02\x00\x00\x05field\x07\x05value\xff\x06\x00\xfa\x0es>*\x09\xe9Y"));
        $this->assertSame('value', $Redis->hget('hash', 'field'));

        try {
            $this->assertSame(true, $Redis->restore('key', 0, "\x00\x01\x00\x06\x00\xcd\x15\x4d\x4c\x99\x42\x7f\xc5"));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\KeysCommandsTrait::wait
     */
    public function test_wait() {
        $Redis = static::$Redis;

        $this->assertTrue(is_int($Redis->wait(2, 1)));
    }
}
