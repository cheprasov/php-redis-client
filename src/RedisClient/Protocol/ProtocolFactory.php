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
namespace RedisClient\Protocol;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Connection\ConnectionFactory;

class ProtocolFactory {

    /**
     * @param AbstractRedisClient $RedisClient
     * @param array $config
     * @return RedisProtocol
     */
    public static function createRedisProtocol(AbstractRedisClient $RedisClient, $config) {
        $onConnect = function() use ($RedisClient, $config) {
            /** @var \RedisClient\RedisClient $RedisClient */
            if (isset($config[AbstractRedisClient::CONFIG_PASSWORD])) {
                $RedisClient->auth($config[AbstractRedisClient::CONFIG_PASSWORD]);
            }
            if (!empty($config[AbstractRedisClient::CONFIG_DATABASE])) {
                $RedisClient->select($config[AbstractRedisClient::CONFIG_DATABASE]);
            }
        };
        return new RedisProtocol(
            ConnectionFactory::createStreamConnection(
                $config[AbstractRedisClient::CONFIG_SERVER],
                $config[AbstractRedisClient::CONFIG_TIMEOUT],
                $config[AbstractRedisClient::CONFIG_CONNECTION],
                $onConnect
            )
        );
    }
}
