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
namespace Test\Integration\Version2x8;

include_once(__DIR__. '/../Version2x6/SetsCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version2x8\SetsCommandsTrait
 */
class SetsCommandsTest extends \Test\Integration\Version2x6\SetsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x8\SetsCommandsTrait::sscan
     */
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
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
