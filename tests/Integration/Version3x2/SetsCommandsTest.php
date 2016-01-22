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

include_once(__DIR__. '/../Version3x0/SetsCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x0\SetsCommandsTest as SetsCommandsTestVersion3x0;

/**
 * @see SetsCommandsTrait
 */
class SetsCommandsTest extends SetsCommandsTestVersion3x0 {

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
