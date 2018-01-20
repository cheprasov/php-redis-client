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
namespace Test\Integration;

use RedisClient\Client\Version\RedisClient2x6;

/**
 * Check Redis Versions
 */
class DefaultDatabaseTest extends \PHPUnit_Framework_TestCase {

    public function test_defaultDatabase() {
        $Redis = new RedisClient2x6([
            'server' => TEST_REDIS_SERVER_2x6_1,
        ]);
        $Redis->flushall();

        for ($i = 0; $i <= 7; ++$i) {
            $this->assertTrue($Redis->select($i));
            $this->assertTrue($Redis->set('db', $i));
        }

        for ($i = 0; $i <= 7; ++$i) {
            $Redis = new RedisClient2x6([
                'server' => TEST_REDIS_SERVER_2x6_1,
                'database' => $i
            ]);
            $this->assertEquals($i, $Redis->get('db'));
        }
    }

}
