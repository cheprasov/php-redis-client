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
namespace Test\Integration\Version2x8;

include(__DIR__. '/../Version2x6/HashesCommandsTest.php');

use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version2x6\HashesCommandsTest as HashesCommandsTestVersion2x6;

/**
 * @see HashesCommandsTrait
 */
class HashesCommandsTest extends HashesCommandsTestVersion2x6 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x8_1;

    /**
     * @var RedisClient2x8
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x8([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    public function test_hscan() {
        $Redis = static::$Redis;

        $this->assertSame(['0', []], $Redis->hscan('key-does-not-exist', 0));

        $hscan = $Redis->hscan('hash', 0);
        $this->assertSame(count(static::$fields) * 2, count($hscan[1]));

        for ($i = 0; $i < count($hscan[1]) ; $i += 2) {
            $key = $hscan[1][$i];
            $value = $hscan[1][$i + 1];
            $this->assertSame((string) static::$fields[$key], $value);
        }

        try {
            $Redis->hscan('string', 'field');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
