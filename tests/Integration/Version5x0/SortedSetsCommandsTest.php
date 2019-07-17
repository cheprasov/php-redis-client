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
namespace Test\Integration\Version5x0;

include_once(__DIR__. '/../Version4x0/SortedSetsCommandsTest.php');

class SortedSetsCommandsTest extends \Test\Integration\Version4x0\SortedSetsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version5x0\SortedSetsCommandsTrait::bzpopmin
     */
    public function test_bzpopmin() {
        $Redis = static::$Redis;
        $Redis->del(['zset1', 'zset2']);
        $Redis->zadd('zset1', [ 'a' => 0, 'b' => 1, 'c' => 2]);
        $this->assertSame(['zset1', 'a', '0'], $Redis->bzpopmin(['zset1', 'zset2'], 1));
        $this->assertSame(['zset1', 'b', '1'], $Redis->bzpopmin(['zset1', 'zset2'], 1));
        $this->assertSame(['zset1', 'c', '2'], $Redis->bzpopmin(['zset1', 'zset2'], 1));
        $this->assertSame(null, $Redis->bzpopmin(['zset1', 'zset2'], 1));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\SortedSetsCommandsTrait::bzpopmax
     */
    public function test_bzpopmax() {
        $Redis = static::$Redis;
        $Redis->del(['zset1', 'zset2']);
        $Redis->zadd('zset1', [ 'a' => 0, 'b' => 1, 'c' => 2]);
        $this->assertSame(['zset1', 'c', '2'], $Redis->bzpopmax(['zset1', 'zset2'], 1));
        $this->assertSame(['zset1', 'b', '1'], $Redis->bzpopmax(['zset1', 'zset2'], 1));
        $this->assertSame(['zset1', 'a', '0'], $Redis->bzpopmax(['zset1', 'zset2'], 1));
        $this->assertSame(null, $Redis->bzpopmin(['zset1', 'zset2'], 1));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\SortedSetsCommandsTrait::zpopmin
     */
    public function test_zpopmin() {
        $Redis = static::$Redis;
        $Redis->del(['zset1', 'zset2']);
        $Redis->zadd('zset1', ['a' => 0, 'b' => 1, 'c' => 2]);
        $this->assertSame(['a', '0'], $Redis->zpopmin('zset1'));
        $this->assertSame(['b', '1'], $Redis->zpopmin('zset1'));
        $this->assertSame(['c', '2'], $Redis->zpopmin('zset1'));
        $this->assertSame([], $Redis->zpopmin('zset1'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version5x0\SortedSetsCommandsTrait::zpopmax
     */
    public function test_zpopmax() {
        $Redis = static::$Redis;
        $Redis->del(['zset1', 'zset2']);
        $Redis->zadd('zset1', ['a' => 0, 'b' => 1, 'c' => 2]);
        $this->assertSame(['c', '2'], $Redis->zpopmax('zset1'));
        $this->assertSame(['b', '1'], $Redis->zpopmax('zset1'));
        $this->assertSame(['a', '0'], $Redis->zpopmax('zset1'));
        $this->assertSame([], $Redis->zpopmax('zset1'));
    }
}
