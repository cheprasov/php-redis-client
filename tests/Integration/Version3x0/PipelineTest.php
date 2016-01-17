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
namespace Test\Integration\Version3x0;

include_once(__DIR__. '/../Version2x8/PipelineTest.php');

use RedisClient\Client\Version\RedisClient3x0;
use Test\Integration\Version2x8\PipelineTest as PipelineTestVersion2x8;

class PipelineTest extends PipelineTestVersion2x8 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x0_1;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x0([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

}
