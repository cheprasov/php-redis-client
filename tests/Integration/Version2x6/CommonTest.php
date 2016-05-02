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

/**
 * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait
 */
class CommonTest extends \PHPUnit_Framework_TestCase {

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

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeRawString
     */
    public function test_executeRawString() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->executeRawString('PING'));

        $this->assertSame(true, $Redis->executeRawString('SET foo bar'));
        $this->assertSame('bar', $Redis->executeRawString('GET foo'));

        $this->assertSame(true, $Redis->executeRawString('SET foo "hello world"'));
        $this->assertSame('hello world', $Redis->executeRawString('GET foo'));

        $this->assertSame(true, $Redis->executeRawString("SET \"\" \"String\r\nwith\r\nnewlines\""));
        $this->assertSame("String\r\nwith\r\nnewlines", $Redis->executeRawString('GET ""'));
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeRaw
     */
    public function test_executeRaw() {
        $Redis = static::$Redis;

        $this->assertSame('PONG', $Redis->executeRaw(['PING']));

        $this->assertSame(true, $Redis->executeRaw(['SET', 'foo', 'bar']));
        $this->assertSame('bar', $Redis->executeRaw(['GET', 'foo']));

        $this->assertSame(true, $Redis->executeRaw(['SET', 'foo', 'hello world']));
        $this->assertSame('hello world', $Redis->executeRaw(['GET', 'foo']));

        $this->assertSame(true, $Redis->executeRaw(['SET', '', "String\r\nwith\r\nnewlines"]));
        $this->assertSame("String\r\nwith\r\nnewlines", $Redis->executeRaw(['GET', '']));
    }

}
