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

use RedisClient\Exception\ErrorResponseException;

include_once(__DIR__. '/../Version5x0/HashesCommandsTest.php');

class HashesCommandsTest extends \Test\Integration\Version5x0\HashesCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\HashesCommandsTrait::hincrbyfloat
     */
    public function test_hincrbyfloat() {
        $Redis = static::$Redis;

        $this->assertSame('1.1', $Redis->hincrbyfloat('key-does-not-exist', 'field', 1.1));
        $this->assertSame('1.1', $Redis->hincrbyfloat('hash', 'field-does-not-exist', '1.1'));
        $this->assertSame('-1.1', $Redis->hincrbyfloat('key-does-not-exist-2', 'field', -1.1));
        $this->assertSame('-1.1', $Redis->hincrbyfloat('hash', 'field-does-not-exist-2', '-1.1'));
        $this->assertSame('4.25159265', $Redis->hincrbyfloat('hash', 'float', 1.11));
        $this->assertSame('48.2', $Redis->hincrbyfloat('hash', 'integer', 6.2));
        $this->assertSame('44.4', $Redis->hincrbyfloat('hash', 'integer', -3.8));
        $this->assertSame('5200', $Redis->hincrbyfloat('', 'e', '2.0e2'));
    }

}
