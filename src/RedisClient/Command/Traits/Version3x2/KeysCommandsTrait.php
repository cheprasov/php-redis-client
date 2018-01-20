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
namespace RedisClient\Command\Traits\Version3x2;

use RedisClient\Command\Traits\Version3x0\KeysCommandsTrait as KeysCommandsTraitVersion3x0;

/**
 * Keys Commands
 * @link http://redis.io/commands#generic
 */
trait KeysCommandsTrait {

    use KeysCommandsTraitVersion3x0;

    /**
     * MIGRATE host port key|"" destination-db timeout [COPY] [REPLACE] [KEYS key [key ...]]
     * Available since 2.6.0.
     * @link http://redis.io/commands/migrate
     *
     * @param string $host
     * @param int $port
     * @param string|string[] $keys
     * @param int $destinationDb
     * @param int $timeout In milliseconds
     * @param bool $copy Available in 3.0 and are not available in 2.6 or 2.8
     * @param bool $replace Available in 3.0 and are not available in 2.6 or 2.8
     * @return bool The command returns True on success.
     */
    public function migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false) {
        $params = [$host, $port, is_string($keys) ? $keys : '', $destinationDb, $timeout];
        if ($copy) {
            $params[] = 'COPY';
        }
        if ($replace) {
            $params[] = 'REPLACE';
        }
        if (is_array($keys)) {
            $params[] = 'KEYS';
            $params[] = $keys;
        }
        return $this->returnCommand(['MIGRATE'], (array)$keys, $params);
    }

    /**
     * TOUCH key [key ...]
     * Alters the last access time of a key
     * Available since 3.2.1
     * @link http://redis.io/commands/touch
     * @link https://github.com/antirez/redis/commit/f1c237cb6a647ad5400b0ebce124fd9802ea7f89
     *
     * @return int Returns the number of existing keys specified.
     */
    public function touch($keys) {
        $keys = (array)$keys;
        return $this->returnCommand(['TOUCH'], $keys, $keys);
    }

}
