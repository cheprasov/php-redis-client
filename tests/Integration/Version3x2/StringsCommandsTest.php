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

include_once(__DIR__. '/../Version3x0/StringsCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use Test\Integration\Version3x0\StringsCommandsTest as StringsCommandsTestVersion3x0;

/**
 * @see \RedisClient\Command\Traits\Version3x2\StringsCommandsTrait
 */
class StringsCommandsTest extends StringsCommandsTestVersion3x0 {

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

    /**
     * @see \RedisClient\Command\Traits\Version3x2\StringsCommandsTrait::bitfield
     */
    public function test_bitfield() {
        $Redis = static::$Redis;

        $this->assertSame([1, 0], $Redis->bitfield('foo', [['INCRBY', 'i5', 100, 1], ['GET', 'u4', 0]]));

        $this->assertSame([1, 1], $Redis->bitfield('bar', [['INCRBY', 'u2', 100, 1], ['OVERFLOW', 'SAT'], ['INCRBY', 'u2', 102, 1]]));
        $this->assertSame([2, 2], $Redis->bitfield('bar', [['INCRBY', 'u2', 100, 1], ['OVERFLOW', 'SAT'], ['INCRBY', 'u2', 102, 1]]));
        $this->assertSame([3, 3], $Redis->bitfield('bar', [['INCRBY', 'u2', 100, 1], ['OVERFLOW', 'SAT'], ['INCRBY', 'u2', 102, 1]]));
        $this->assertSame([0, 3], $Redis->bitfield('bar', [['INCRBY', 'u2', 100, 1], ['OVERFLOW', 'SAT'], ['INCRBY', 'u2', 102, 1]]));

        $this->assertSame([null], $Redis->bitfield('bar', [['OVERFLOW', 'FAIL'], ['INCRBY', 'u2', 102, 1]]));

        $this->assertSame([0], $Redis->bitfield('space', [['SET', 'i8', 0, 32]]));
        $this->assertSame(' ', $Redis->get('space'));
        $this->assertSame([32], $Redis->bitfield('space', [['SET', 'i8', 0, 32]]));

        $this->assertSame([0, 0, 0, 0], $Redis->bitfield('word', [
            ['SET', 'i8', '#0', ord('w')],
            ['SET', 'i8', '#1', ord('o')],
            ['SET', 'i8', '#2', ord('r')],
            ['SET', 'i8', '#3', ord('d')],
        ]));
        $this->assertSame('word', $Redis->get('word'));
        $this->assertSame([114], $Redis->bitfield('word', [['SET', 'i8', 16, ord('o')],]));
        $this->assertSame('wood', $Redis->get('word'));
        $this->assertSame([87, 79, 79, 68], $Redis->bitfield('word', [
            ['INCRBY', 'i8', 0, -32],
            ['INCRBY', 'i8', 8, -32],
            ['INCRBY', 'i8', '#2', -32],
            ['INCRBY', 'i8', '#3', -32],
        ]));
        $this->assertSame('WOOD', $Redis->get('word'));

        $this->assertSame([1, 0], $Redis->bitfield('one', [
            ['INCRBY', 'u1', 0, 1],
            ['INCRBY', 'u1', 0, 1],
        ]));

        $this->assertSame([1, 2, 3, 0], $Redis->bitfield('two', [
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
        ]));

        $this->assertSame([1, 2, 3, 3], $Redis->bitfield('two', [
            ['OVERFLOW', 'SAT'],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
        ]));

        $this->assertSame([1, 2, 3, null], $Redis->bitfield('three', [
            ['OVERFLOW', 'FAIL'],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
            ['INCRBY', 'u2', 0, 1],
        ]));

        $this->assertSame([null], $Redis->bitfield('four', [
            ['OVERFLOW', 'FAIL'],
            ['INCRBY', 'u2', 0, 255],
        ]));

        /*
         Setting a 5 bits unsigned integer to value 23 at offset 7 into a bitmap
         previously set to all zeroes, will produce the following representation
         +--------+--------+
         |00000001|01110000|
         +--------+--------+
         When offsets and integer sizes are aligned to bytes boundaries, this is the same as big endian,
         however when such alignment does not exist, its important to also understand how the bits inside
         a byte are ordered.
        */
        $this->assertSame([0], $Redis->bitfield('five', ['SET', 'u5', 7, 23]));
        $ints = unpack('n', $Redis->get('five'));
        $this->assertSame(368, $ints[1]);
    }

}
