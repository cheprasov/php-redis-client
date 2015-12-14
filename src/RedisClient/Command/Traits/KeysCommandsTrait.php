<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\IntegerParameter;
use RedisClient\Command\Parameter\KeyParameter;
use RedisClient\Command\Parameter\KeysParameter;
use RedisClient\Command\Parameter\LimitParameter;
use RedisClient\Command\Parameter\StringParameter;

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
            new Command('DEL', new KeysParameter($keys))
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
            new Command('DUMP', new KeyParameter($key))
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
            new Command('EXISTS', new KeysParameter($key))
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
                new KeyParameter($key),
                new IntegerParameter($seconds),
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
                new KeyParameter($key),
                new IntegerParameter($timestamp),
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
            new Command('KEYS', new StringParameter($pattern))
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
                new StringParameter($host),
                new IntegerParameter($port),
                new KeyParameter($key),
                new StringParameter($destinationDb),
                new IntegerParameter($timeout)
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
                new KeyParameter($key),
                new IntegerParameter($db)
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
            new StringParameter($subcommand)
        ];
        if ($arguments) {
            $params[] = new KeysParameter($arguments);
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
            new Command('PERSIST', new KeyParameter($key))
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
                new KeyParameter($key),
                new IntegerParameter($milliseconds)
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
                new KeyParameter($key),
                new IntegerParameter($millisecondsTimestamp)
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
            new Command('PTTL', new KeyParameter($key))
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
                new KeyParameter($key),
                new KeyParameter($newkey),
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
                new KeyParameter($key),
                new KeyParameter($newkey),
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
            new KeyParameter($key),
            new IntegerParameter($ttl),
            new StringParameter($serializedValue),
        ];
        if ($replace) {
            $params[] = new StringParameter($replace);
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
            new IntegerParameter($cursor)
        ];
        if ($pattern) {
            $params[] = new StringParameter('MATCH');
            $params[] = new StringParameter($pattern);
        }
        if ($count) {
            $params[] = new StringParameter('COUNT');
            $params[] = new IntegerParameter($count);
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
            new KeyParameter($key)
        ];
        if ($pattern) {
            $params[] = new StringParameter('BY');
            $params[] = new StringParameter($pattern);
        }
        if ($limit) {
            $params[] = new StringParameter('LIMIT');
            $params[] = new LimitParameter($limit);
        }
        if ($patterns) {
            foreach ((array) $patterns as $p) {
                $params[] = new StringParameter('GET');
                $params[] = new StringParameter($p);
            }
        }
        if (!is_null($asc)) {
            $params[] = new StringParameter($asc ? 'ASC' : 'DESC');
        }
        if ($alpha) {
            $params[] = new StringParameter('ALPHA');
        }
        if ($destination) {
            $params[] = new StringParameter('STORE');
            $params[] = new KeyParameter($destination);
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
            new Command('TTL', new KeyParameter($key))
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
            new Command('TYPE', new KeyParameter($key))
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
                new IntegerParameter($numslaves),
                new IntegerParameter($timeout),
            ])
        );
    }

}
