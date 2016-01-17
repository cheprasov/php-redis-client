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
namespace Test\Integration\Version3x2;

include_once(__DIR__. '/../Version3x0/HashesCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x0\HashesCommandsTest as HashesCommandsTestVersion3x0;

/**
 * @see HashesCommandsTrait
 */
class HashesCommandsTest extends HashesCommandsTestVersion3x0 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;

    /**
     * @var RedisClient3x2
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    public function test_hstrlen() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->hstrlen('hash', 'some-field'));
        $this->assertSame(1, $Redis->hsetnx('hash', 'some-field', 'good'));
        $this->assertSame(4, $Redis->hstrlen('hash', 'some-field'));

        try {
            $Redis->hstrlen('string', 'field');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
