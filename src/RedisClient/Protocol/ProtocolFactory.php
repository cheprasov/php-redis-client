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
namespace RedisClient\Protocol;

use RedisClient\Connection\ConnectionFactory;

class ProtocolFactory {

    /**
     * @param string $server
     * @param int $timeout
     * @return RedisProtocol
     */
    public static function createRedisProtocol($server, $timeout) {
        return new RedisProtocol(ConnectionFactory::createStreamConnection($server, $timeout));
    }
}
