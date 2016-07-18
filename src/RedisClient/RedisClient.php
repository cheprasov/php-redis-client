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
namespace RedisClient;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Client\Version\RedisClient2x8;
use RedisClient\Client\Version\RedisClient3x0;
use RedisClient\Client\Version\RedisClient3x2;

switch (ClientFactory::getDefaultRedisVersion()) {
    case '2.6':
        class RedisClient extends RedisClient2x6 {};
        break;
    case '2.8':
        class RedisClient extends RedisClient2x8 {};
        break;
    case '3.0':
        class RedisClient extends RedisClient3x0 {};
        break;
    case '3.2':
    default:
        class RedisClient extends RedisClient3x2 {};
        break;
}
