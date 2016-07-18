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
namespace RedisClient\Pipeline;

use RedisClient\ClientFactory;
use RedisClient\Pipeline\Version\Pipeline2x6;
use RedisClient\Pipeline\Version\Pipeline2x8;
use RedisClient\Pipeline\Version\Pipeline3x0;
use RedisClient\Pipeline\Version\Pipeline3x2;

switch (ClientFactory::getDefaultRedisVersion()) {
    case '2.6':
        class Pipeline extends Pipeline2x6 {};
        break;
    case '2.8':
        class Pipeline extends Pipeline2x8 {};
        break;
    case '3.0':
        class Pipeline extends Pipeline3x0 {};
        break;
    case '3.2':
    default:
        class Pipeline extends Pipeline3x2 {};
        break;
}
