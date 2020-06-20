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
namespace RedisClient;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Client\Version\RedisClient4x0;
use RedisClient\Client\Version\RedisClient5x0;
use RedisClient\Client\Version\RedisClient6x0;

switch (ClientFactory::getDefaultRedisVersion()) {
    case ClientFactory::REDIS_VERSION_2x6:
        class RedisClient extends RedisClient2x6 {};
        break;
    case ClientFactory::REDIS_VERSION_2x8:
        class RedisClient extends RedisClient2x8 {};
        break;
    case ClientFactory::REDIS_VERSION_3x0:
        class RedisClient extends RedisClient3x0 {};
        break;
    case ClientFactory::REDIS_VERSION_3x2:
        class RedisClient extends RedisClient3x2 {};
        break;
    case ClientFactory::REDIS_VERSION_4x0:
        class RedisClient extends RedisClient4x0 {};
        break;
    case ClientFactory::REDIS_VERSION_5x0:
        class RedisClient extends RedisClient5x0 {};
        break;
    case ClientFactory::REDIS_VERSION_6x0:
        class RedisClient extends RedisClient6x0 {};
        break;
    default:
        class RedisClient extends RedisClient6x0 {};
        break;
}
