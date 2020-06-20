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

include_once(__DIR__. '/../Version4x0/ConnectionCommandsTest.php');

/**
 * @see \RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait
 */
class ConnectionCommandsTest extends \Test\Integration\Version4x0\ConnectionCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait::auth
     */
    public function test_auth() {
        $Redis = static::$Redis;

        try {
            $this->assertSame(true, $Redis->auth('password'));
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR AUTH <password> called without any password configured for the default user. Are you sure your configuration is correct?', $Ex->getMessage());
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ConnectionCommandsTrait::clientCetredir
     */
    public function test_clientCetredir() {
        $Redis = static::$Redis;

        $this->assertSame(-1, $Redis->clientCetredir());
    }

}
