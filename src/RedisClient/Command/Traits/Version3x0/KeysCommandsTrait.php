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
namespace RedisClient\Command\Traits\Version3x0;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version2x8\KeysCommandsTrait as KeysCommandsTraitVersion28;

/**
 * trait KeysCommandsTrait
 */
trait KeysCommandsTrait {

    use KeysCommandsTraitVersion28;

    /**
     * EXISTS key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/exists
     *
     * @param string|string[] $keys
     * @return int 1 if the key exists. 0 if the key does not exist.
     * Or the number of keys existing among the ones specified as arguments.
     */
    public function exists($keys) {
        return $this->returnCommand(['EXISTS'], Parameter::keys($keys));
    }

    /**
     * MIGRATE host port key destination-db timeout [COPY] [REPLACE]
     * Available since 2.6.0.
     * @link http://redis.io/commands/migrate
     *
     * @param string $host
     * @param int $port
     * @param string $key
     * @param int $destinationDb
     * @param int $timeout In milliseconds
     * @param bool $copy Available in 3.0 and are not available in 2.6 or 2.8
     * @param bool $replace Available in 3.0 and are not available in 2.6 or 2.8
     * @return bool The command returns True on success.
     */
    public function migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false) {
        $params = [
            Parameter::string($host),
            Parameter::port($port),
            Parameter::key($key),
            Parameter::integer($destinationDb),
            Parameter::integer($timeout)
        ];
        if ($copy) {
            $params[] = Parameter::string('COPY');
        }
        if ($replace) {
            $params[] = Parameter::string('REPLACE');
        }
        return $this->returnCommand(['MIGRATE'], $params);
    }

    /**
     * RESTORE key ttl serialized-value [REPLACE]
     * Available since 2.6.0.
     * Time complexity: O(1) to create the new key and additional O(N*M) to reconstruct the serialized value
     * @link http://redis.io/commands/restore
     *
     * @param string $key
     * @param int $ttl In milliseconds
     * @param string $serializedValue
     * @param bool|false $replace Redis 3.0 or greater
     * @return bool The command returns True on success.
     */
    public function restore($key, $ttl, $serializedValue, $replace = false) {
        $params = [Parameter::key($key), Parameter::integer($ttl), Parameter::string($serializedValue),];
        if ($replace) {
            $params[] = Parameter::string('REPLACE');
        }
        return $this->returnCommand(['RESTORE'], $params);
    }

    /**
     * WAIT numslaves timeout
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/wait
     *
     * @param int $numslaves
     * @param int $timeout In milliseconds
     * @return int The command returns the number of slaves reached
     * by all the writes performed in the context of the current connection.
     */
    public function wait($numslaves, $timeout) {
        return $this->returnCommand(['WAIT'], [Parameter::integer($numslaves), Parameter::integer($timeout),]);
    }

}
