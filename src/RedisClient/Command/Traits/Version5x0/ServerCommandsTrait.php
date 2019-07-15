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
namespace RedisClient\Command\Traits\Version5x0;

use RedisClient\Command\Traits\Version3x2\ServerCommandsTrait as ServerCommandsTraitVersion3x2;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion3x2;

    /**
     * LOLWUT
     * Available since 5.0.0.
     * @link http://antirez.com/news/123
     *
     * @param number|null $param1
     * @param number|null $param2
     * @param number|null $param3
     * @return bool
     */
    public function lolwut($param1 = null, $param2 = null, $param3 = null) {
        $params = [];
        if (isset($param1)) {
            $params[] = $param1;

            if (isset($param2)) {
                $params[] = $param2;

                if (isset($param3)) {
                    $params[] = $param3;
                }
            }
        }
        return $this->returnCommand(['LOLWUT'], null, $params ? $params : null);
    }

    /**
     * REPLICAOF host port
     * Available since 5.0.0.
     * @link https://redis.io/commands/replicaof
     *
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function replicaof($host, $port) {
        return $this->returnCommand(['REPLICAOF'], null, [$host, $port]);
    }

}
