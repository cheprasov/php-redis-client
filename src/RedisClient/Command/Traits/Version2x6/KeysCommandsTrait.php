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
namespace RedisClient\Command\Traits\Version2x6;

use RedisClient\Command\Parameter\Parameter;

/**
 * Keys Commands
 * @link http://redis.io/commands#generic
 */
trait KeysCommandsTrait {

    /**
     * DEL key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of keys that will be removed.
     * @link http://redis.io/commands/del
     *
     * @param string|string[] $keys
     * @return int The number of keys that were removed.
     */
    public function del($keys) {
        $keys = (array)$keys;
        return $this->returnCommand(['DEL'], $keys, $keys);
    }

    /**
     * DUMP key
     * Available since 2.6.0.
     * Time complexity: O(1) to access the key and additional O(N*M) to serialized it,
     * where N is the number of Redis objects composing the value and M their average size.
     * @link http://redis.io/commands/dump
     *
     * @param string $key
     * @return string The serialized value.
     */
    public function dump($key) {
        return $this->returnCommand(['DUMP'], $key, [$key]);
    }

    /**
     * EXISTS key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/exists
     *
     * @param string $key
     * @return int 1 if the key exists. 0 if the key does not exist.
     * Or the number of keys existing among the ones specified as arguments.
     */
    public function exists($key) {
        return $this->returnCommand(['EXISTS'], $key, [$key]);
    }

    /**
     * EXPIRE key seconds
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/expire
     *
     * @param string $key
     * @param int $seconds
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set.
     */
    public function expire($key, $seconds) {
        return $this->returnCommand(['EXPIRE'], $key, [$key, $seconds]);
    }

    /**
     * EXPIREAT key timestamp
     * Available since 1.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/expireat
     *
     * @param string $key
     * @param int $timestamp
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set (see: EXPIRE).
     */
    public function expireAt($key, $timestamp) {
        return $this->returnCommand(['EXPIREAT'], $key, [$key, $timestamp]);
    }

    /**
     * KEYS pattern
     * Available since 1.0.0.
     * Time complexity: O(N)
     * @link http://redis.io/commands/keys
     *
     * @param string $pattern
     * @return array List of keys matching pattern.
     */
    public function keys($pattern) {
        return $this->returnCommand(['KEYS'], null, [$pattern]);
    }

    /**
     * MIGRATE host port key destination-db timeout
     * Available since 2.6.0.
     * @link http://redis.io/commands/migrate
     *
     * @param string $host
     * @param int $port
     * @param string $key
     * @param int $destinationDb
     * @param int $timeout In milliseconds
     * @return bool The command returns True on success.
     */
    public function migrate($host, $port, $key, $destinationDb, $timeout) {
        return $this->returnCommand(['MIGRATE'], $key, [$host, $port, $key, $destinationDb, $timeout]);
    }

    /**
     * MOVE key db
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/move
     *
     * @param string $key
     * @param int $db
     * @return int 1 if key was moved. 0 if key was not moved.
     */
    public function move($key, $db) {
        return $this->returnCommand(['MOVE'], $key, [$key, $db]);
    }

    /**
     * OBJECT subcommand [arguments [arguments ...]]
     * Available since 2.2.3.
     * Time complexity: O(1) for all the currently implemented subcommands.
     * @link http://redis.io/commands/object
     *
     * @param string $subcommand REFCOUNT|ENCODING|IDLETIME
     * @param null|string|string[] $arguments
     * @return int|string
     */
    public function object($subcommand, $arguments = null) {
        $params = [$subcommand];
        if ($arguments) {
            $params[] = (array) $arguments;
        }
        return $this->returnCommand(['OBJECT'], null, $params);
    }

    /**
     * PERSIST key
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/persist
     *
     * @param string $key
     * @return int 1 if the timeout was removed.
     * 0 if key does not exist or does not have an associated timeout.
     */
    public function persist($key) {
        return $this->returnCommand(['PERSIST'], $key, [$key]);
    }

    /**
     * PEXPIRE key milliseconds
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/pexpire
     *
     * @param string $key
     * @param int $milliseconds
     * @return int 1 if the timeout was set.
     * 0 if key does not exist or the timeout could not be set.
     */
    public function pexpire($key, $milliseconds) {
        return $this->returnCommand(['PEXPIRE'], $key, [$key, $milliseconds]);
    }

    /**
     * PEXPIREAT key milliseconds-timestamp
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/pexpireat
     *
     * @param string $key
     * @param int $millisecondsTimestamp
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set (see: EXPIRE).
     */
    public function pexpireat($key, $millisecondsTimestamp) {
        return $this->returnCommand(['PEXPIREAT'], $key, [$key, $millisecondsTimestamp]);
    }

    /**
     * PTTL key
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/pttl
     *
     * @param string $key
     * @return int TTL in milliseconds, or a negative value in order to signal an error.
     */
    public function pttl($key) {
        return $this->returnCommand(['PTTL'], $key, [$key]);
    }

    /**
     * RANDOMKEY
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/randomkey
     *
     * @return string|null The random key, or null when the database is empty.
     */
    public function randomkey() {
        return $this->returnCommand(['RANDOMKEY']);
    }

    /**
     * RENAME key newkey
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/rename
     *
     * @param string $key
     * @param string $newkey
     * @return bool True
     */
    public function rename($key, $newkey) {
        $keys = [$key, $newkey];
        return $this->returnCommand(['RENAME'], $keys, $keys);
    }

    /**
     * RENAMENX key newkey
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/renamenx
     *
     * @param string $key
     * @param string $newkey
     * @return int 1 if key was renamed to newkey. 0 if newkey already exists.
     */
    public function renamenx($key, $newkey) {
        $keys = [$key, $newkey];
        return $this->returnCommand(['RENAMENX'], $keys, $keys);
    }

    /**
     * RESTORE key ttl serialized-value
     * Available since 2.6.0.
     * Time complexity: O(1) to create the new key and additional O(N*M) to reconstruct the serialized value
     * @link http://redis.io/commands/restore
     *
     * @param string $key
     * @param int $ttl In milliseconds
     * @param string $serializedValue
     * @return bool The command returns True on success.
     */
    public function restore($key, $ttl, $serializedValue) {
        return $this->returnCommand(['RESTORE'], $key, [$key, $ttl, $serializedValue]);
    }

    /**
     * SORT key [BY pattern] [LIMIT offset count] [GET pattern [GET pattern ...]] [ASC|DESC] [ALPHA] [STORE destination]
     * Available since 1.0.0.
     * Time complexity: O(N+M*log(M)) or O(N)
     * @link http://redis.io/commands/sort
     *
     * @param string $key
     * @param string|null $pattern
     * @param int|array|null $limit
     * @param string|string[]|null $patterns
     * @param bool|null $asc
     * @param bool $alpha
     * @param string|null $destination
     * @return mixed
     */
    public function sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null) {
        $params = [$key];
        if ($pattern) {
            $params[] = 'BY';
            $params[] = $pattern;
        }
        if ($limit) {
            $params[] = 'LIMIT';
            $params[] = Parameter::limit($limit);
        }
        if ($patterns) {
            foreach ((array)$patterns as $p) {
                $params[] = 'GET';
                $params[] = $p;
            }
        }
        if (isset($asc)) {
            $params[] = $asc ? 'ASC' : 'DESC';
        }
        if ($alpha) {
            $params[] = 'ALPHA';
        }
        if ($destination) {
            $params[] = 'STORE';
            $params[] = $destination;
        }
        return $this->returnCommand(['SORT'], $key, $params);
    }

    /**
     * TTL key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/ttl
     *
     * @param string $key
     * @return int TTL in seconds, or a negative value in order to signal an error
     */
    public function ttl($key) {
        return $this->returnCommand(['TTL'], $key, [$key]);
    }

    /**
     * TYPE key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/type
     *
     * @param string $key
     * @return string
     */
    public function type($key) {
        return $this->returnCommand(['TYPE'], $key, [$key]);
    }

}
