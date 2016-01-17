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
use RedisClient\Exception\ErrorResponseException;

/**
 * @see ServerCommandsTrait
 */
class ServerCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @var RedisClient2x6
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
        static::$Redis->scriptFlush();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
        static::$Redis->scriptFlush();
    }

    /**
     * @see ServerCommandsTrait::bgrewriteaof
     */
    public function _test_bgrewriteaof() {
        $Redis = static::$Redis;

        $this->assertSame('Background append only file rewriting started', $Redis->bgrewriteaof());
        try {
            $this->assertSame(true, (bool) $Redis->bgrewriteaof());
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see ServerCommandsTrait::bgsave
     */
    public function _test_bgsave() {
        $Redis = static::$Redis;

        $this->assertSame('Background saving started', $Redis->bgsave());
        try {
            $this->assertSame(true, (bool) $Redis->bgsave());
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see ServerCommandsTrait::clientGetname
     */
    public function test_clientGetname() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->clientGetname());
        $this->assertSame(true, $Redis->clientSetname('test-connection'));
        $this->assertSame('test-connection', $Redis->clientGetname());
    }

    /**
     * @see ServerCommandsTrait::clientList
     */
    public function _test_clientList() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->clientList());
        $this->assertSame(true, $Redis->clientSetname('test-connection'));
        $this->assertSame('test-connection', $Redis->clientGetname());
    }

}
