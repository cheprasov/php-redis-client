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
namespace Test\Integration\Version4x0;

include_once(__DIR__. '/../Version3x2/SetsCommandsTest.php');

use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Exception\ErrorResponseException;
use Test\Integration\Version3x2\SetsCommandsTest as SetsCommandsTestVersion3x2;

/**
 * @see \RedisClient\Command\Traits\Version4x0\SetsCommandsTrait
 */
class SetsCommandsTest extends SetsCommandsTestVersion3x2 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_4x0_1;

    /**
     * @var RedisClient4x0
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient4x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version4x0\SetsCommandsTrait::spop
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
