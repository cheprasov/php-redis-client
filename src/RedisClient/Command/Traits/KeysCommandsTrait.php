<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

trait KeysCommandsTrait {

    /**
     * DEL key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of keys that will be removed.
     *
     * @param string|string[] $keys
     * @return int The number of keys that were removed.
     */
    public function del($keys) {
        return $this->returnCommand(
            new Command('DEL', Parameter::keys($keys))
        );
    }

    /**
     * DUMP key
     * Available since 2.6.0.
     * Time complexity: O(1) to access the key and additional O(N*M) to serialized it,
     * where N is the number of Redis objects composing the value and M their average size.
     *
     * @param string $key
     * @return string The serialized value.
     */
    public function dump($key) {
        return $this->returnCommand(
            new Command('DUMP', Parameter::key($key))
        );
    }

    /**
     * EXISTS key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string|string[] $key
     * @return int 1 if the key exists. 0 if the key does not exist.
     * Or the number of keys existing among the ones specified as arguments.
     */
    public function exists($key) {
        return $this->returnCommand(
            new Command('EXISTS', Parameter::keys($key))
        );
    }

    /**
     * EXPIRE key seconds
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $seconds
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set.
     */
    public function expire($key, $seconds) {
        return $this->returnCommand(
            new Command('EXPIRE', [
                Parameter::key($key),
                Parameter::integer($seconds),
            ])
        );
    }

    /**
     * EXPIREAT key timestamp
     * Available since 1.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $timestamp
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set (see: EXPIRE).
     */
    public function expireAt($key, $timestamp) {
        return $this->returnCommand(
            new Command('EXPIREAT', [
                Parameter::key($key),
                Parameter::integer($timestamp),
            ])
        );
    }

    /**
     * KEYS pattern
     * Available since 1.0.0.
     * Time complexity: O(N)
     *
     * @param string $pattern
     * @return array List of keys matching pattern.
     */
    public function keys($pattern) {
        return $this->returnCommand(
            new Command('KEYS', Parameter::string($pattern))
        );
    }

    /**
     * MIGRATE host port key destination-db timeout [COPY] [REPLACE]
     * Available since 2.6.0.
     *
     * @param string $host
     * @param int $port
     * @param string $key
     * @param string $destinationDb
     * @param int $timeout
     * @return bool The command returns TRUE on success.
     */
    public function migrate($host, $port, $key, $destinationDb, $timeout) {
        return $this->returnCommand(
            new Command('MIGRATE', [
                Parameter::string($host),
                Parameter::integer($port),
                Parameter::key($key),
                Parameter::string($destinationDb),
                Parameter::integer($timeout)
            ])
        );
    }

    /**
     * MOVE key db
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $db
     * @return int 1 if key was moved. 0 if key was not moved.
     */
    public function move($key, $db) {
        return $this->returnCommand(
            new Command('MOVE', [
                Parameter::key($key),
                Parameter::integer($db)
            ])
        );
    }

    /**
     * OBJECT subcommand [arguments [arguments ...]]
     * Available since 2.2.3.
     * Time complexity: O(1) for all the currently implemented subcommands.
     *
     * @param string $subcommand
     * @param null|string|string[] $arguments
     * @return mixed
     */
    public function object($subcommand, $arguments = null) {
        $params = [
            Parameter::string($subcommand)
        ];
        if ($arguments) {
            $params[] = Parameter::keys($arguments);
        }
        return $this->returnCommand(
            new Command('OBJECT', $params)
        );
    }

    /**
     * PERSIST key
     * Available since 2.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return int 1 if the timeout was removed.
     * 0 if key does not exist or does not have an associated timeout.
     */
    public function persist($key) {
        return $this->returnCommand(
            new Command('PERSIST', Parameter::key($key))
        );
    }

    /**
     * PEXPIRE key milliseconds
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @param $key
     * @return int 1 if the timeout was set.
     * 0 if key does not exist or the timeout could not be set.
     */
    public function pexpire($key, $milliseconds) {
        return $this->returnCommand(
            new Command('PEXPIRE',[
                Parameter::key($key),
                Parameter::integer($milliseconds)
            ])
        );
    }

    /**
     * PEXPIREAT key milliseconds-timestamp
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $millisecondsTimestamp
     * @return int 1 if the timeout was set. 0 if key does not exist or the timeout could not be set (see: EXPIRE).
     */
    public function pexpireat($key, $millisecondsTimestamp) {
        return $this->returnCommand(
            new Command('PEXPIREAT',[
                Parameter::key($key),
                Parameter::integer($millisecondsTimestamp)
            ])
        );
    }

    /**
     * PTTL key
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @param $key
     * @return int TTL in milliseconds, or a negative value in order to signal an error (see the description above).
     */
    public function pttl($key) {
        return $this->returnCommand(
            new Command('PTTL', Parameter::key($key))
        );
    }

    /**
     * RANDOMKEY
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @return string|null The random key, or null when the database is empty.
     */
    public function randomkey() {
        return $this->returnCommand(
            new Command('RANDOMKEY')
        );
    }

    /**
     * RENAME key newkey
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string $newkey
     * @return bool
     */
    public function rename($key, $newkey) {
        return $this->returnCommand(
            new Command('RENAME', [
                Parameter::key($key),
                Parameter::key($newkey),
            ])
        );
    }

    /**
     * RENAMENX key newkey
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string $newkey
     * @return int 1 if key was renamed to newkey. 0 if newkey already exists.
     */
    public function renamenx($key, $newkey) {
        return $this->returnCommand(
            new Command('RENAMENX', [
                Parameter::key($key),
                Parameter::key($newkey),
            ])
        );
    }

    /**
     * RESTORE key ttl serialized-value [REPLACE]
     * Available since 2.6.0.
     * Time complexity: O(1) to create the new key and additional O(N*M) to reconstruct the serialized value
     *
     * @param string $key
     * @param int $ttl
     * @param string $serializedValue
     * @param bool|false $replace
     * @return bool The command returns TRUE on success.
     */
    public function restore($key, $ttl, $serializedValue, $replace = false) {
        $params = [
            Parameter::key($key),
            Parameter::integer($ttl),
            Parameter::string($serializedValue),
        ];
        if ($replace) {
            $params[] = Parameter::string($replace);
        }
        return $this->returnCommand(
            new Command('RESTORE', $params)
        );
    }

    /**
     * SCAN cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration.
     *
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function scan($cursor, $pattern = null, $count = null) {
        $params = [
            Parameter::integer($cursor)
        ];
        if ($pattern) {
            $params[] = Parameter::string('MATCH');
            $params[] = Parameter::string($pattern);
        }
        if ($count) {
            $params[] = Parameter::string('COUNT');
            $params[] = Parameter::integer($count);
        }
        return $this->returnCommand(
            new Command('SCAN', $params)
        );
    }

    /**
     * SORT key [BY pattern] [LIMIT offset count] [GET pattern [GET pattern ...]] [ASC|DESC] [ALPHA] [STORE destination]
     * Available since 1.0.0.
     * Time complexity: O(N+M*log(M)) or O(N)
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
        $params = [
            Parameter::key($key)
        ];
        if ($pattern) {
            $params[] = Parameter::string('BY');
            $params[] = Parameter::string($pattern);
        }
        if ($limit) {
            $params[] = Parameter::string('LIMIT');
            $params[] = Parameter::limit($limit);
        }
        if ($patterns) {
            foreach ((array) $patterns as $p) {
                $params[] = Parameter::string('GET');
                $params[] = Parameter::string($p);
            }
        }
        if (!is_null($asc)) {
            $params[] = Parameter::string($asc ? 'ASC' : 'DESC');
        }
        if ($alpha) {
            $params[] = Parameter::string('ALPHA');
        }
        if ($destination) {
            $params[] = Parameter::string('STORE');
            $params[] = Parameter::key($destination);
        }
        return $this->returnCommand(
            new Command('SORT', $params)
        );
    }

    /**
     * TTL key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param $key
     * @return int TTL in seconds, or a negative value in order to signal an error
     */
    public function ttl($key) {
        return $this->returnCommand(
            new Command('TTL', Parameter::key($key))
        );
    }

    /**
     * TYPE key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return string
     */
    public function type($key) {
        return $this->returnCommand(
            new Command('TYPE', Parameter::key($key))
        );
    }

    /**
     * WAIT numslaves timeout
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param int $numslaves
     * @param int $timeout
     * @return int The command returns the number of slaves reached
     * by all the writes performed in the context of the current connection.
     */
    public function wait($numslaves, $timeout) {
        return $this->returnCommand(
            new Command('TYPE', [
                Parameter::integer($numslaves),
                Parameter::integer($timeout),
            ])
        );
    }

}
