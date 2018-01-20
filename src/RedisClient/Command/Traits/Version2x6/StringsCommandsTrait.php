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
use RedisClient\Exception\InvalidArgumentException;

/**
 * Strings Commands
 * @link http://redis.io/commands#string
 */
trait StringsCommandsTrait {

    /**
     * APPEND key value
     * Available since 2.0.0.
     * Time complexity: O(1).
     * @link http://redis.io/commands/append
     *
     * @param string $key
     * @param string $value
     * @return int The length of the string after the append operation.
     */
    public function append($key, $value) {
        return $this->returnCommand(['APPEND'], $key, [$key, $value]);
    }

    /**
     * BITCOUNT key [start end]
     * Available since 2.6.0.
     * Time complexity: O(N)
     * @link http://redis.io/commands/bitcount
     *
     * @param string $key
     * @param null|int $start
     * @param null|int $end
     * @return int The number of bits set to 1.
     */
    public function bitcount($key, $start = null, $end = null) {
        if (isset($start) xor isset($end)) {
            throw new InvalidArgumentException('Start and End must be used together');
        }
        $params = [$key];
        if (isset($start, $end)) {
            $params[] = $start;
            $params[] = $end;
        }
        return $this->returnCommand(['BITCOUNT'], $key, $params);
    }

    /**
     * BITOP operation destkey key [key ...]
     * Available since 2.6.0.
     * Time complexity: O(N)
     * @link http://redis.io/commands/bitop
     *
     * @param string $operation AND, OR, XOR and NOT
     * @param string $destkey
     * @param string|string[] $keys
     * @return int The size of the string stored in the destination key,
     * that is equal to the size of the longest input string.
     */
    public function bitop($operation, $destkey, $keys) {
        $keys = (array)$keys;
        $allKeys = $keys;
        array_unshift($allKeys, $destkey);
        return $this->returnCommand(['BITOP'], $allKeys, [$operation, $destkey, $keys]);
    }

    /**
     * DECR key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/decr
     *
     * @param string $key
     * @return int The value of key after the decrement
     */
    public function decr($key) {
        return $this->returnCommand(['DECR'], $key, [$key]);
    }

    /**
     * DECRBY key decrement
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/decrby
     *
     * @param string $key
     * @param int $decrement
     * @return int The value of key after the decrement
     */
    public function decrby($key, $decrement) {
        return $this->returnCommand(['DECRBY'], $key, [$key, $decrement]);
    }

    /**
     * GET key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/get
     *
     * @param string $key
     * @return string|null
     */
    public function get($key) {
        return $this->returnCommand(['GET'], $key, [$key]);
    }

    /**
     * GETBIT key offset
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/getbit
     *
     * @param string $key
     * @param int $offset
     * @return int The bit value stored at offset.
     */
    public function getbit($key, $offset) {
        return $this->returnCommand(['GETBIT'], $key, [$key, $offset]);
    }

    /**
     * GETRANGE key start end
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the length of the returned string.
     * @link http://redis.io/commands/getrange
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @return string
     */
    public function getrange($key, $start, $end) {
        return $this->returnCommand(['GETRANGE'], $key, [$key, $start, $end]);
    }

    /**
     * SUBSTR key start end
     * @deprecated
     * @see StringsCommandsTrait::getrange
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @return string
     */
    public function substr($key, $start, $end) {
        return $this->returnCommand(['SUBSTR'], $key, [$key, $start, $end]);
    }

    /**
     * GETSET key value
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/getset
     *
     * @param string $key
     * @param string $value
     * @return string|null The old value stored at key, or nil when key did not exist.
     */
    public function getset($key, $value) {
        return $this->returnCommand(['GETSET'], $key, [$key, $value]);
    }

    /**
     * INCR key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/incr
     *
     * @param string $key
     * @return int The value of key after the increment
     */
    public function incr($key) {
        return $this->returnCommand(['INCR'], $key, [$key]);
    }

    /**
     * INCRBY key increment
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/incrby
     *
     * @param string $key
     * @param int $increment
     * @return int The value of key after the increment
     */
    public function incrby($key, $increment) {
        return $this->returnCommand(['INCRBY'], $key, [$key, $increment]);
    }

    /**
     * INCRBYFLOAT key increment
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/incrbyfloat
     *
     * @param string $key
     * @param integer|float $increment
     * @return string
     */
    public function incrbyfloat($key, $increment) {
        return $this->returnCommand(['INCRBYFLOAT'], $key, [$key, $increment]);
    }

    /**
     * MGET key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of keys to retrieve.
     * @link http://redis.io/commands/mget
     *
     * @param string|string[] $keys
     * @return array
     */
    public function mget($keys) {
        $keys = (array)$keys;
        return $this->returnCommand(['MGET'], $keys, $keys);
    }

    /**
     * MSET key value [key value ...]
     * Available since 1.0.1.
     * Time complexity: O(N) where N is the number of keys to set.
     * @link http://redis.io/commands/mset
     *
     * @param array $keyValues
     * @return bool always True since MSET can't fail.
     */
    public function mset(array $keyValues) {
        return $this->returnCommand(['MSET'], array_keys($keyValues), Parameter::assocArray($keyValues));
    }

    /**
     * MSETNX key value [key value ...]
     * Available since 1.0.1.
     * Time complexity: O(N) where N is the number of keys to set.
     * @link http://redis.io/commands/msetnx
     *
     * @param array $keyValues
     * @return int 1 if the all the keys were set. 0 if no key was set (at least one key already existed).
     */
    public function msetnx(array $keyValues) {
        return $this->returnCommand(['MSETNX'], array_keys($keyValues), Parameter::assocArray($keyValues));
    }

    /**
     * PSETEX key milliseconds value
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/psetex
     *
     * @param string $key
     * @param int $milliseconds
     * @param string $value
     * @return bool
     */
    public function psetex($key, $milliseconds, $value) {
        return $this->returnCommand(['PSETEX'], $key, [$key, $milliseconds, $value]);
    }

    /**
     * SET key value [EX seconds | PX milliseconds] [NX|XX]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/set
     *
     * @param string $key
     * @param string $value
     * @param null|int $seconds
     * @param null|int $milliseconds
     * @param null|string $exist NX - if not exist, XX - if it already exist.
     * @return bool|null
     * @throw InvalidArgumentException
     */
    public function set($key, $value, $seconds = null, $milliseconds = null, $exist = null) {
        $params = [$key, $value];
        if (isset($seconds)) {
            $params[] = 'EX';
            $params[] = $seconds;
        }
        if (isset($milliseconds)) {
            $params[] = 'PX';
            $params[] = $milliseconds;
        }
        if (isset($exist)) {
            $params[] = $exist;
        }
        return $this->returnCommand(['SET'], $key, $params);
    }

    /**
     * SETBIT key offset value
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/setbit
     *
     * @param string $key
     * @param int $offset
     * @param int|bool $bit 0/1 or true/false
     * @return int The original bit value stored at offset.
     */
    public function setbit($key, $offset, $bit) {
        return $this->returnCommand(['SETBIT'], $key, [$key, $offset, $bit]);
    }

    /**
     * SETEX key seconds value
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/setex
     *
     * @param string $key
     * @param int $seconds
     * @param string $value
     * @return bool
     */
    public function setex($key, $seconds, $value) {
        return $this->returnCommand(['SETEX'], $key, [$key, $seconds, $value]);
    }

    /**
     * SETNX key value
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/setnx
     *
     * @param string $key
     * @param string $value
     * @return int 1 if the key was set, 0 if the key was not set
     */
    public function setnx($key, $value) {
        return $this->returnCommand(['SETNX'], $key, [$key, $value]);
    }

    /**
     * SETRANGE key offset value
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/setrange
     *
     * @param string $key
     * @param int $offset
     * @param string $value
     * @return int The length of the string after it was modified by the command.
     */
    public function setrange($key, $offset, $value) {
        return $this->returnCommand(['SETRANGE'], $key, [$key, $offset, $value]);
    }

    /**
     * STRLEN key
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/strlen
     *
     * @param string $key
     * @return int The length of the string at key, or 0 when key does not exist.
     */
    public function strlen($key) {
        return $this->returnCommand(['STRLEN'], $key, [$key]);
    }

}
