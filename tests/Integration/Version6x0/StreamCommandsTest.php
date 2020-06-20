<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <acheprasov84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Test\Integration\Version6x0;

include_once(__DIR__ . '/../BaseVersionTest.php');

/**
 * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait
 */
class StreamCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xack
     */
    public function test_xack() {
        $result = static::$Redis->xack('mystream','mygroup', '1526569495631-0');
        $this->assertEquals(0, $result);
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xadd
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xlen
     */
    public function test_xadd() {
        $this->assertEquals(0, static::$Redis->xlen('mystream'));

        $result = static::$Redis->xadd('mystream', '*', ['name' => 'Sara', 'surname' => 'OConnor']);
        $this->assertEquals(1, preg_match('/^\d+-\d$/', $result));
        $this->assertEquals(1, static::$Redis->xlen('mystream'));

        $result = static::$Redis->xadd('mystream', '*', ['field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3']);
        $this->assertEquals(1, preg_match('/^\d+-\d$/', $result));
        $this->assertEquals(2, static::$Redis->xlen('mystream'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xdel
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xrange
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xadd
     */
    public function test_xdel() {
        $id1 = static::$Redis->xadd('mystream', '*', ['a' => 1]);
        $id2 = static::$Redis->xadd('mystream', '*', ['b' => 2]);
        $id3 = static::$Redis->xadd('mystream', '*', ['c' => 3]);

        $range = static::$Redis->xrange('mystream', '-', '+');
        $this->assertEquals(3, count($range));
        $this->assertEquals([$id1, ['a', 1]], $range[0]);
        $this->assertEquals([$id2, ['b', 2]], $range[1]);
        $this->assertEquals([$id3, ['c', 3]], $range[2]);

        $range = static::$Redis->xrange('mystream', '-', '+', 2);
        $this->assertEquals(2, count($range));
        $this->assertEquals([$id1, ['a', 1]], $range[0]);
        $this->assertEquals([$id2, ['b', 2]], $range[1]);

        $this->assertEquals(1, static::$Redis->xdel('mystream', $id1));
        $range = static::$Redis->xrange('mystream', '-', '+');
        $this->assertEquals(2, count($range));
        $this->assertEquals([$id2, ['b', 2]], $range[0]);
        $this->assertEquals([$id3, ['c', 3]], $range[1]);

        $this->assertEquals(1, static::$Redis->xdel('mystream', $id3));
        $range = static::$Redis->xrange('mystream', '-', '+');
        $this->assertEquals(1, count($range));
        $this->assertEquals([$id2, ['b', 2]], $range[0]);

        $this->assertEquals(0, static::$Redis->xdel('mystream', $id3));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xinfo
     */
    public function test_xinfo() {
        static::$Redis->xadd('mystream', '*', ['a' => 1]);
        static::$Redis->xadd('mystream', '*', ['b' => 2]);
        static::$Redis->xadd('mystream', '*', ['c' => 3]);

        $result = static::$Redis->xinfo(null, null, null, 'mystream');
        $this->assertEquals(14, count($result));
        $this->assertEquals('length', $result[0]);
        $this->assertEquals(3, $result[1]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xrevrange
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xdel
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xadd
     */
    public function test_xrevrange() {
        $id1 = static::$Redis->xadd('mystream', '*', ['a' => 1]);
        $id2 = static::$Redis->xadd('mystream', '*', ['b' => 2]);
        $id3 = static::$Redis->xadd('mystream', '*', ['c' => 3]);

        $range = static::$Redis->xrevrange('mystream', '+', '-');
        $this->assertEquals(3, count($range));
        $this->assertEquals([$id3, ['c', 3]], $range[0]);
        $this->assertEquals([$id2, ['b', 2]], $range[1]);
        $this->assertEquals([$id1, ['a', 1]], $range[2]);

        $range = static::$Redis->xrevrange('mystream', '+', '-', 2);
        $this->assertEquals(2, count($range));
        $this->assertEquals([$id3, ['c', 3]], $range[0]);
        $this->assertEquals([$id2, ['b', 2]], $range[1]);

        $this->assertEquals(1, static::$Redis->xdel('mystream', $id1));
        $range = static::$Redis->xrevrange('mystream', '+', '-');
        $this->assertEquals(2, count($range));
        $this->assertEquals([$id3, ['c', 3]], $range[0]);
        $this->assertEquals([$id2, ['b', 2]], $range[1]);

        $this->assertEquals(1, static::$Redis->xdel('mystream', $id3));
        $range = static::$Redis->xrevrange('mystream', '+', '-');
        $this->assertEquals(1, count($range));
        $this->assertEquals([$id2, ['b', 2]], $range[0]);

        $this->assertEquals(0, static::$Redis->xdel('mystream', $id3));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\StreamsCommandsTrait::xtrim
     */
    public function test_xtrim() {
        $id = static::$Redis->xadd('mystream', '*', ['field1' => 'A', 'field2' => 'B', 'field3' => 'C', 'field4' => 'D']);

        $this->assertEquals(0, static::$Redis->xtrim('mystream', 2));
        $range = static::$Redis->xrange('mystream', '-', '+');
        $this->assertEquals(1, count($range));
        $this->assertEquals([$id, ['field1', 'A', 'field2', 'B', 'field3', 'C', 'field4', 'D']], $range[0]);
    }

}
