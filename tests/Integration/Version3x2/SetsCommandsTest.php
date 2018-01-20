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
namespace Test\Integration\Version3x2;

include_once(__DIR__. '/../Version3x0/SetsCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version3x2\SetsCommandsTrait
 */
class SetsCommandsTest extends \Test\Integration\Version3x0\SetsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version3x2\SetsCommandsTrait::spop
     */
    public function test_spop() {
        $Redis = static::$Redis;

        $this->assertSame(5, $Redis->sadd('bar', $foo = ['a', 'b', 'c', 'd', 'e']));

        $moo = [];
        $m = $Redis->spop('bar', 1);
        $this->assertSame(1, count($m));
        $this->assertSame(true, in_array($m[0], $foo));
        $this->assertSame(false, in_array($m[0], $moo));
        $moo += $m;

        $m = $Redis->spop('bar', 2);
        $this->assertSame(2, count($m));
        $this->assertSame(true, in_array($m[0], $foo));
        $this->assertSame(true, in_array($m[1], $foo));
        $this->assertSame(false, in_array($m[0], $moo));
        $this->assertSame(false, in_array($m[1], $moo));
        $moo += $m;

        $m = $Redis->spop('bar', 3);
        $this->assertSame(2, count($m));
        $this->assertSame(true, in_array($m[0], $foo));
        $this->assertSame(true, in_array($m[1], $foo));
        $this->assertSame(false, in_array($m[0], $moo));
        $this->assertSame(false, in_array($m[1], $moo));
        $moo += $m;

        $Redis->set('foo', 'bar');
        try {
            $Redis->spop('foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
