<?php

namespace Test\Integration;

use PHPUnit_Framework_TestCase;
use RedisClient\RedisClient;

class AbstractCommandsTest extends PHPUnit_Framework_TestCase {

    /**
     * @var RedisClient
     */
    protected static $Redis;

    /**
     * @var array
     */
    protected static $config;

    /**
     * @var string
     */
    protected static $version;

    /**
     * @var array
     */
    protected static $fields;

    const REDIS_RESPONSE_ERROR_WRONGTYPE = 'WRONGTYPE Operation against a key holding the wrong kind of value';

    const REDIS_RESPONSE_ERROR_HASH_NOT_INTEGER = 'ERR hash value is not an integer';
    const REDIS_RESPONSE_ERROR_HASH_NOT_FLOAT = 'ERR hash value is not a valid float';

    const REDIS_RESPONSE_ERROR_STRING_NOT_INTEGER = 'ERR value is not an integer or out of range';
    const REDIS_RESPONSE_ERROR_STRING_NOT_FLOAT = 'ERR value is not a valid float';
    const REDIS_RESPONSE_ERROR_STRING_EXPIRE_TIME = 'ERR invalid expire time in set';
    const REDIS_RESPONSE_ERROR_STRING_EXPIRE_TIME_SETEX = 'ERR invalid expire time in setex';

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$config = include(__DIR__. '/redis-test-config.php');
        static::$Redis = new RedisClient(self::$config[0]);
        static::$version = static::$Redis->info('server')['redis_version'];
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

    public function test_abstract() {
        $this->assertTrue(true);
    }
}
