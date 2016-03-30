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

include_once(__DIR__. '/../Version2x6/StringsCommandsTest.php');

use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version2x6\StringsCommandsTest as StringsCommandsTestVersion2x6;

/**
 * @see \RedisClient\Command\Traits\Version2x8\StringsCommandsTrait
 */
class StringsCommandsTest extends StringsCommandsTestVersion2x6 {

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

    /**
     * @see \RedisClient\Command\Traits\Version2x8\StringsCommandsTrait::bitpos
     */
    public function test_bitpos() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('mykey', "\xff\xf0\x00"));
        $this->assertSame(12, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', "\x00\xff\xf0"));
        $this->assertSame(8, $Redis->bitpos('mykey', 1, 0));
        $this->assertSame(16, $Redis->bitpos('mykey', 1, 2));

        $this->assertSame(true, $Redis->set('mykey', "\x00\x00\x00"));
        $this->assertSame(-1, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', "\x00\x00\x00"));
        $this->assertSame(-1, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', chr(0b00001111)));
        $this->assertSame(4, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', chr(0b00000011)));
        $this->assertSame(6, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', chr(0b00000000).chr(0b11111111)));
        $this->assertSame(8, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', chr(0b00000000).chr(0b00000001)));
        $this->assertSame(15, $Redis->bitpos('mykey', 1));
        $this->assertSame(0, $Redis->bitpos('mykey', 0));

        $this->assertSame(true, $Redis->set('mykey', chr(0b10000000).chr(0b00000001)));
        $this->assertSame(0, $Redis->bitpos('mykey', 1));
        $this->assertSame(1, $Redis->bitpos('mykey', 0));

        try {
            $Redis->bitpos('hash', 0);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
