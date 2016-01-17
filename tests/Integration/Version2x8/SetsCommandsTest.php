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

include_once(__DIR__. '/../Version2x6/SetsCommandsTest.php');

use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version2x6\SetsCommandsTest as SetsCommandsTestVersion2x6;

/**
 * @see SetsCommandsTrait
 */
class SetsCommandsTest extends SetsCommandsTestVersion2x6 {

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

    public function test_sscan() {
        $Redis = static::$Redis;

        $this->assertSame(['0', []], $Redis->sscan('bar', 0));
        $this->assertSame(5, $Redis->sadd('bar', $foo = ['aa', 'ba', 'ca', 'da', 'ea']));
        $list = $Redis->sscan('bar', 0);
        sort($list[1]);
        $this->assertEquals(['0', $foo], $list);

        $list = $Redis->sscan('bar', 0, 'a*');
        $this->assertEquals(['0', ['aa']], $list);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sscan('foo', 0);
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
