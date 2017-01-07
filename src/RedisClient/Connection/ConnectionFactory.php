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
namespace RedisClient\Connection;

class ConnectionFactory {

    /**
     * @param string $server
     * @param int $timeout
     * @param callable $onConnect
     * @return StreamConnection
     */
    public static function createStreamConnection($server, $timeout, $onConnect = null) {
        $Connection = new StreamConnection($server, $timeout);
        if ($onConnect) {
            $Connection->onConnect($onConnect);
        }
        return $Connection;
    }

}
