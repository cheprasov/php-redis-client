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

include_once(__DIR__. '/../Version2x6/HashesCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version2x8\HashesCommandsTrait
 */
class HashesCommandsTest extends \Test\Integration\Version2x6\HashesCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x8\HashesCommandsTrait::hscan
     */
    public function test_hscan() {
        $Redis = static::$Redis;

        $this->assertSame(['0', []], $Redis->hscan('key-does-not-exist', 0));

        $hscan = $Redis->hscan('hash', 0);
        $this->assertSame(count(static::$fields) * 2, count($hscan[1]));

        for ($i = 0; $i < count($hscan[1]) ; $i += 2) {
            $key = $hscan[1][$i];
            $value = $hscan[1][$i + 1];
            $this->assertSame((string) static::$fields[$key], $value);
        }

        try {
            $Redis->hscan('string', 'field');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
