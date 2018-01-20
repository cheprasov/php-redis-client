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
namespace RedisClient\Pipeline;

use RedisClient\ClientFactory;
use RedisClient\Pipeline\Version\Pipeline2x6;
use RedisClient\Pipeline\Version\Pipeline2x8;
use RedisClient\Pipeline\Version\Pipeline3x0;
use RedisClient\Pipeline\Version\Pipeline3x2;
use RedisClient\Pipeline\Version\Pipeline4x0;

switch (ClientFactory::getDefaultRedisVersion()) {
    case ClientFactory::REDIS_VERSION_2x6:
        class Pipeline extends Pipeline2x6 {};
        break;
    case ClientFactory::REDIS_VERSION_2x8:
        class Pipeline extends Pipeline2x8 {};
        break;
    case ClientFactory::REDIS_VERSION_3x0:
        class Pipeline extends Pipeline3x0 {};
        break;
    case ClientFactory::REDIS_VERSION_3x2:
        class Pipeline extends Pipeline3x2 {};
        break;
    case ClientFactory::REDIS_VERSION_4x0:
        class Pipeline extends Pipeline4x0 {};
        break;
    default:
        class Pipeline extends Pipeline3x2 {};
        break;
}
