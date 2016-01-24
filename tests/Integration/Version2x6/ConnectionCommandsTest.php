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
namespace Test\Integration\Version2x6;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\ClientFactory;
use RedisClient\Exception\ErrorResponseException;

/**
 * @see ConnectionCommandsTrait
 */
class ConnectionCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass() {
        static::$Redis->flushall();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
    }


    public function test_auth() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->auth('password'));
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR Client sent AUTH, but no password is set', $Ex->getMessage());
        }
    }

    public function test_echoMessage() {
        $Redis = static::$Redis;

        $this->assertSame('message', $Redis->echoMessage('message'));
        $this->assertSame('', $Redis->echoMessage(''));
        $this->assertSame('foo bar', $Redis->echoMessage('foo bar'));
    }

    public function test_echo() {
        $Redis = static::$Redis;

        $this->assertSame('message', $Redis->echo('message'));
        $this->assertSame('', $Redis->echo(''));
        $this->assertSame('foo bar', $Redis->echo('foo bar'));
    }

    public function test_ping() {
        $Redis = static::$Redis;
        $this->assertSame('PONG', $Redis->ping());
    }

    /*
    public function test_quit() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->quit());
    }
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
