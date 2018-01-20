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
namespace Test\Integration\Version2x6;

include_once(__DIR__ . '/../BaseVersionTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait
 */
class SetsCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sadd
     */
    public function test_sadd() {
        $Redis = static::$Redis;

        $this->assertSame(1, $Redis->sadd('set', 'foo'));
        $this->assertSame(0, $Redis->sadd('set', 'foo'));
        $this->assertSame(1, $Redis->sadd('set', ['foo', 'bar']));
        $this->assertSame(3, $Redis->sadd('set', ['a', 'b', 'c']));
        $this->assertSame(0, $Redis->sadd('set', ['a', 'b', 'c']));

        $Redis->set('foo', 'bar');
        try {
            $Redis->sadd('foo', 'bar');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::scard
     */
    public function test_scard() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->scard('set'));
        $this->assertSame(1, $Redis->sadd('set', 'foo'));
        $this->assertSame(1, $Redis->scard('set'));
        $this->assertSame(1, $Redis->sadd('set', ['foo', 'bar']));
        $this->assertSame(2, $Redis->scard('set'));
        $this->assertSame(3, $Redis->sadd('set', ['a', 'b', 'c']));
        $this->assertSame(5, $Redis->scard('set'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->scard('foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sdiff
     */
    public function test_sdiff() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $diff = $Redis->sdiff('bar');
        sort($diff);
        $this->assertSame(['a', 'b', 'c'], $diff);

        $diff = $Redis->sdiff('foo');
        sort($diff);
        $this->assertSame(['b', 'c', 'd'], $diff);

        $diff = $Redis->sdiff(['bar', 'bar']);
        $this->assertSame([], $diff);

        $diff = $Redis->sdiff(['bar', 'foo']);
        $this->assertSame(['a'], $diff);

        $diff = $Redis->sdiff(['foo', 'bar']);
        $this->assertSame(['d'], $diff);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sdiff(['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sdiffstore
     */
    public function test_sdiffstore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $this->assertSame(3, $Redis->sdiffstore('store', 'bar'));
        $diff = $Redis->sdiff('store');
        sort($diff);
        $this->assertSame(['a', 'b', 'c'], $diff);

        $this->assertSame(3, $Redis->sdiffstore('store', 'foo'));
        $diff = $Redis->sdiff('store');
        sort($diff);
        $this->assertSame(['b', 'c', 'd'], $diff);

        $this->assertSame(0, $Redis->sdiffstore('store', ['bar', 'bar']));
        $diff = $Redis->sdiff(['store']);
        $this->assertSame([], $diff);

        $this->assertSame(1, $Redis->sdiffstore('store', ['bar', 'foo']));
        $diff = $Redis->sdiff('store');
        $this->assertSame(['a'], $diff);

        $this->assertSame(1, $Redis->sdiffstore('store', ['foo', 'bar']));
        $diff = $Redis->sdiff('store');
        $this->assertSame(['d'], $diff);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sdiffstore('store', ['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sinter
     */
    public function test_sinter() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $inter = $Redis->sinter('bar');
        sort($inter);
        $this->assertSame(['a', 'b', 'c'], $inter);

        $inter = $Redis->sinter('foo');
        sort($inter);
        $this->assertSame(['b', 'c', 'd'], $inter);

        $inter = $Redis->sinter(['bar', 'bar']);
        sort($inter);
        $this->assertSame(['a', 'b', 'c'], $inter);

        $inter = $Redis->sinter(['bar', 'foo']);
        sort($inter);
        $this->assertSame(['b', 'c'], $inter);

        $inter = $Redis->sinter(['foo', 'bar']);
        sort($inter);
        $this->assertSame(['b', 'c'], $inter);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sinter(['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sinterstore
     */
    public function test_sinterstore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $this->assertSame(3, $Redis->sinterstore('store', 'bar'));
        $inter = $Redis->sinter('store');
        sort($inter);
        $this->assertSame(['a', 'b', 'c'], $inter);

        $this->assertSame(3, $Redis->sinterstore('store', 'foo'));
        $inter = $Redis->sinter('store');
        sort($inter);
        $this->assertSame(['b', 'c', 'd'], $inter);

        $this->assertSame(3, $Redis->sinterstore('store', ['bar', 'bar']));
        $inter = $Redis->sinter('store');
        sort($inter);
        $this->assertSame(['a', 'b', 'c'], $inter);

        $this->assertSame(2, $Redis->sinterstore('store', ['bar', 'foo']));
        $inter = $Redis->sinter('store');
        sort($inter);
        $this->assertSame(['b', 'c'], $inter);

        $this->assertSame(2, $Redis->sinterstore('store', ['foo', 'bar']));
        $inter = $Redis->sinter('store');
        sort($inter);
        $this->assertSame(['b', 'c'], $inter);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sinterstore('store', ['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sismember
     */
    public function test_sismember() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(1, $Redis->sismember('bar', 'a'));
        $this->assertSame(1, $Redis->sismember('bar', 'c'));
        $this->assertSame(0, $Redis->sismember('bar', 'd'));
        $this->assertSame(0, $Redis->sismember('foo', 'a'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->sismember('foo', 'bar');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::smembers
     */
    public function test_smembers() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $members = $Redis->smembers('bar');
        sort($members);
        $this->assertSame(['a', 'b', 'c'], $members);

        $this->assertSame([], $Redis->smembers('foo'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->smembers('foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::smove
     */
    public function test_smove() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $this->assertSame(1, $Redis->smove('bar', 'foo', 'a'));
        $this->assertSame(0, $Redis->sismember('bar', 'a'));
        $this->assertSame(1, $Redis->sismember('foo', 'a'));

        $this->assertSame(0, $Redis->smove('bar', 'foo', 'a'));

        $this->assertSame(1, $Redis->smove('foo', 'bar', 'a'));
        $this->assertSame(1, $Redis->sismember('bar', 'a'));
        $this->assertSame(0, $Redis->sismember('foo', 'a'));

        $this->assertSame(1, $Redis->smove('foo', 'bar', 'b'));
        $this->assertSame(1, $Redis->sismember('bar', 'b'));
        $this->assertSame(0, $Redis->sismember('foo', 'b'));

        $this->assertSame(0, $Redis->smove('bar', 'foo', 'e'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->smove('foo', 'bar', 'd');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::spop
     */
    public function test_spop() {
        $Redis = static::$Redis;

        $this->assertSame(5, $Redis->sadd('bar', $foo = ['a', 'b', 'c', 'd', 'e']));

        $moo = [];
        foreach ($foo as $f) {
            $m = $Redis->spop('bar');
            $this->assertSame(true, in_array($m, $foo));
            $this->assertSame(false, in_array($m, $moo));
            $moo[] = $m;
        }
        $this->assertSame(null, $Redis->spop('bar'));
        $this->assertSame(null, $Redis->spop('foo'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->spop('foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::srandmember
     */
    public function test_srandmember() {
        $Redis = static::$Redis;

        $this->assertSame(5, $Redis->sadd('bar', $foo = ['a', 'b', 'c', 'd', 'e']));
        $this->assertSame(true, in_array($Redis->srandmember('bar'), $foo));
        $this->assertSame(true, in_array($Redis->srandmember('bar'), $foo));
        $this->assertSame(true, in_array($Redis->srandmember('bar'), $foo));
        $this->assertSame(true, in_array($Redis->srandmember('bar'), $foo));
        $this->assertSame(5, $Redis->scard('bar'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->srandmember('foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::srem
     */
    public function test_srem() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->srem('bar', 'foo'));
        $this->assertSame(5, $Redis->sadd('bar', $foo = ['a', 'b', 'c', 'd', 'e']));
        $this->assertSame(0, $Redis->srem('bar', 'foo'));
        $this->assertSame(1, $Redis->srem('bar', 'a'));
        $this->assertSame(0, $Redis->srem('bar', 'a'));
        $this->assertSame(0, $Redis->sismember('bar', 'a'));

        $this->assertSame(0, $Redis->srem('bar', ['foo', 'a']));
        $this->assertSame(2, $Redis->srem('bar', ['b', 'c']));
        $this->assertSame(0, $Redis->sismember('bar', 'b'));
        $this->assertSame(0, $Redis->sismember('bar', 'c'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->srem('foo', 'bar');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sunion
     */
    public function test_sunion() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $union = $Redis->sunion('bar'); sort($union);
        $this->assertSame(['a', 'b', 'c'], $union);

        $union = $Redis->sunion('foo'); sort($union);
        $this->assertSame(['b', 'c', 'd'], $union);

        $union = $Redis->sunion(['bar', 'bar']); sort($union);
        $this->assertSame(['a', 'b', 'c'], $union);

        $union = $Redis->sunion(['bar', 'foo']); sort($union);
        $this->assertSame(['a', 'b', 'c', 'd'], $union);

        $union = $Redis->sunion(['foo', 'bar']); sort($union);
        $this->assertSame(['a', 'b', 'c', 'd'], $union);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sunion(['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\SetsCommandsTrait::sunionstore
     */
    public function test_sunionstore() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->sadd('bar', ['a', 'b', 'c']));
        $this->assertSame(3, $Redis->sadd('foo', ['b', 'c', 'd']));

        $this->assertSame(3, $Redis->sunionstore('store', 'bar'));
        $union = $Redis->sunion('store'); sort($union);
        $this->assertSame(['a', 'b', 'c'], $union);

        $this->assertSame(3, $Redis->sunionstore('store', 'foo'));
        $union = $Redis->sunion('store'); sort($union);
        $this->assertSame(['b', 'c', 'd'], $union);

        $this->assertSame(3, $Redis->sunionstore('store', ['bar', 'bar']));
        $union = $Redis->sunion('store'); sort($union);
        $this->assertSame(['a', 'b', 'c'], $union);

        $this->assertSame(4, $Redis->sunionstore('store', ['bar', 'foo']));
        $union = $Redis->sunion('store'); sort($union);
        $this->assertSame(['a', 'b', 'c', 'd'], $union);

        $this->assertSame(4, $Redis->sunionstore('store', ['foo', 'bar']));
        $union = $Redis->sunion('store'); sort($union);
        $this->assertSame(['a', 'b', 'c', 'd'], $union);

        $Redis->set('foo', 'bar');
        try {
            $Redis->sunionstore('store', ['foo', 'bar']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
