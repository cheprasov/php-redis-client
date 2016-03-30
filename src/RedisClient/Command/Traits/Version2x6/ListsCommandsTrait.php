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
namespace RedisClient\Command\Traits\Version2x6;

use RedisClient\Command\Response\ResponseParser;

/**
 * Lists Commands
 * @link http://redis.io/commands#list
 */
trait ListsCommandsTrait {

    /**
     * BLPOP key [key ...] timeout
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/blpop
     *
     * @param string|string[] $keys
     * @param int $timeout In seconds
     * @return array|null [list => value]
     */
    public function blpop($keys, $timeout) {
        return $this->returnCommand(['BLPOP'], [(array) $keys, $timeout], ResponseParser::PARSE_ASSOC_ARRAY);
    }

    /**
     * BRPOP key [key ...] timeout
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/brpop
     *
     * @param string|string[] $keys
     * @param int $timeout
     * @return array|null [list => value]
     */
    public function brpop($keys, $timeout) {
        return $this->returnCommand(['BRPOP'], [(array) $keys, $timeout], ResponseParser::PARSE_ASSOC_ARRAY);
    }

    /**
     * BRPOPLPUSH source destination timeout
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/brpoplpush
     *
     * @param string $source
     * @param string $destination
     * @param int $timeout
     * @return mixed The element being popped from source and pushed to destination.
     * If timeout is reached, a Null reply is returned.
     */
    public function brpoplpush($source, $destination, $timeout) {
        return $this->returnCommand(['BRPOPLPUSH'], [$source, $destination, $timeout]);
    }

    /**
     * LINDEX key index
     * Available since 1.0.0.
     * Time complexity: O(N) or O(1)
     * @link http://redis.io/commands/lindex
     *
     * @param string $key
     * @param int $index
     * @return string|null The requested element, or null when index is out of range.
     */
    public function lindex($key, $index) {
        return $this->returnCommand(['LINDEX'], [$key, $index]);
    }

    /**
     * LINSERT key BEFORE|AFTER pivot value
     * Available since 2.2.0.
     * Time complexity: O(N) or O(1)
     * @link http://redis.io/commands/linsert
     *
     * @param string $key
     * @param bool|true $after
     * @param string $pivot
     * @param string $value
     * @return int The length of the list after the insert operation,
     * or -1 when the value pivot was not found. Or 0 when key was not found.
     */
    public function linsert($key, $after = true, $pivot, $value) {
        return $this->returnCommand(['LINSERT'], [$key, $after ? 'AFTER' : 'BEFORE', $pivot, $value]);
    }

    /**
     * LLEN key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/llen
     *
     * @param string $key
     * @return int The length of the list at key.
     */
    public function llen($key) {
        return $this->returnCommand(['LLEN'], [$key]);
    }


    /**
     * LPOP key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/lpop
     *
     * @param string $key
     * @return string|null The value of the first element, or null when key does not exist.
     */
    public function lpop($key) {
        return $this->returnCommand(['LPOP'], [$key]);
    }

    /**
     * LPUSH key value [value ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/lpush
     *
     * @param string $key
     * @param string|string[] $values
     * @return int The length of the list after the push operations.
     */
    public function lpush($key, $values) {
        return $this->returnCommand(['LPUSH'], [$key, (array) $values]);
    }

    /**
     * LPUSHX key value
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/lpushx
     *
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operation.
     */
    public function lpushx($key, $value) {
        return $this->returnCommand(['LPUSHX'], [$key, $value]);
    }

    /**
     * LRANGE key start stop
     * Available since 1.0.0.
     * Time complexity: O(S+N) where S is the distance of start offset from HEAD for small lists.
     * @link http://redis.io/commands/lrange
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return array List of elements in the specified range.
     */
    public function lrange($key, $start, $stop) {
        return $this->returnCommand(['LRANGE'], [$key, $start, $stop]);
    }

    /**
     * LREM key count value
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the length of the list.
     * @link http://redis.io/commands/lrem
     *
     * @param string $key
     * @param int $count
     * @param string $value
     * @return int The number of removed elements.
     */
    public function lrem($key, $count, $value) {
        return $this->returnCommand(['LREM'], [$key, $count, $value]);
    }

    /**
     * LSET key index value
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the length of the list.
     * Setting either the first or the last element of the list is O(1).
     * @link http://redis.io/commands/lset
     *
     * @param string $key
     * @param int $index
     * @param string $value
     * @return bool
     */
    public function lset($key, $index, $value) {
        return $this->returnCommand(['LSET'], [$key, $index, $value]);
    }

    /**
     * LTRIM key start stop
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of elements to be removed by the operation.
     * @link http://redis.io/commands/ltrim
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return bool
     */
    public function ltrim($key, $start, $stop) {
        return $this->returnCommand(['LTRIM'], [$key, $start, $stop]);
    }

    /**
     * RPOP key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/rpop
     *
     * @param string $key
     * @return string|null The value of the last element, or null when key does not exist.
     */
    public function rpop($key) {
        return $this->returnCommand(['RPOP'], [$key]);
    }

    /**
     * RPOPLPUSH source destination
     * Available since 1.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/rpoplpush
     *
     * @param string $source
     * @param string $destination
     * @return string The element being popped and pushed.
     */
    public function rpoplpush($source, $destination) {
        return $this->returnCommand(['RPOPLPUSH'], [$source, $destination]);
    }

    /**
     * RPUSH key value [value ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/rpush
     *
     * @param string $key
     * @param string|string[] $values
     * @return int The length of the list after the push operation.
     */
    public function rpush($key, $values) {
        return $this->returnCommand(['RPUSH'], [$key, (array) $values]);
    }

    /**
     * RPUSHX key value
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/rpushx
     *
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operation.
     */
    public function rpushx($key, $value) {
        return $this->returnCommand(['RPUSHX'], [$key, $value]);
    }

}
