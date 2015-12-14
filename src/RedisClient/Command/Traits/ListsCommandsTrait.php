<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\IntegerParameter;
use RedisClient\Command\Parameter\KeyParameter;
use RedisClient\Command\Parameter\KeysParameter;
use RedisClient\Command\Parameter\StringParameter;
use RedisClient\Command\Parameter\StringsParameter;

trait ListsCommandsTrait {

    /**
     * BLPOP key [key ...] timeout
     * Available since 2.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $timeout
     * @return array
     */
    public function blpop($key, $timeout) {
        return $this->returnCommand(
            new Command('BLPOP', [
                new KeysParameter($key),
                new IntegerParameter($timeout),
            ])
        );
    }

    /**
     * BRPOP key [key ...] timeout
     * Available since 2.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int $timeout
     * @return array
     */
    public function brpop($key, $timeout) {
        return $this->returnCommand(
            new Command('BRPOP', [
                new KeysParameter($key),
                new IntegerParameter($timeout),
            ])
        );
    }

    /**
     * BRPOPLPUSH source destination timeout
     * Available since 2.2.0.
     * Time complexity: O(1)
     *
     * @param string $source
     * @param string $destination
     * @param int $timeout
     * @return mixed The element being popped from source and pushed to destination.
     * If timeout is reached, a Null reply is returned.
     */
    public function brpoplpush($source, $destination, $timeout) {
        return $this->returnCommand(
            new Command('BRPOPLPUSH', [
                new KeyParameter($source),
                new KeyParameter($destination),
                new IntegerParameter($timeout)
            ])
        );
    }

    /**
     * LINDEX key index
     * Available since 1.0.0.
     * Time complexity: O(N) or O(1)
     *
     * @param string $key
     * @param int $index
     * @return string|null The requested element, or null when index is out of range.
     */
    public function lindex($key, $index) {
        return $this->returnCommand(
            new Command('LINDEX', [
                new KeysParameter($key),
                new IntegerParameter($index),
            ])
        );
    }

    /**
     * LINSERT key BEFORE|AFTER pivot value
     * Available since 2.2.0.
     * Time complexity: O(N) or O(1)
     *
     * @param string $key
     * @param bool|true $before
     * @param string $pivot
     * @param string $value
     * @return int The length of the list after the insert operation, or -1 when the value pivot was not found.
     */
    public function linsert($key, $before = true, $pivot, $value) {
        return $this->returnCommand(
            new Command('LINSERT', [
                new KeyParameter($key),
                new StringParameter($before ? 'BEFORE' : 'AFTER'),
                new StringParameter($pivot),
                new StringParameter($value),
            ])
        );
    }

    /**
     * LLEN key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return int The length of the list at key.
     */
    public function llen($key) {
        return $this->returnCommand(
            new Command('LLEN', new KeyParameter($key))
        );
    }


    /**
     * LPOP key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return string|null The value of the first element, or null when key does not exist.
     */
    public function lpop($key) {
        return $this->returnCommand(
            new Command('LPOP', new KeyParameter($key))
        );
    }

    /**
     * LPUSH key value [value ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string|string[] $value
     * @return int The length of the list after the push operations.
     */
    public function lpush($key, $value) {
        return $this->returnCommand(
            new Command('LPUSH', [
                new KeysParameter($key),
                new StringsParameter($value),
            ])
        );
    }

    /**
     * LPUSHX key value
     * Available since 2.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operation.
     */
    public function lpushx($key, $value) {
        return $this->returnCommand(
            new Command('LPUSHX', [
                new KeysParameter($key),
                new StringParameter($value),
            ])
        );
    }

    /**
     * LRANGE key start stop
     * Available since 1.0.0.
     * Time complexity: O(S+N) where S is the distance of start offset from HEAD for small lists
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return array List of elements in the specified range.
     */
    public function lrange($key, $start, $stop) {
        return $this->returnCommand(
            new Command('LRANGE', [
                new KeyParameter($key),
                new IntegerParameter($start),
                new IntegerParameter($stop)
            ])
        );
    }

    /**
     * LREM key count value
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the length of the list.
     *
     * @param string $key
     * @param int $count
     * @param string $value
     * @return int The number of removed elements.
     */
    public function lrem($key, $count, $value) {
        return $this->returnCommand(
            new Command('LREM', [
                new KeyParameter($key),
                new IntegerParameter($count),
                new StringParameter($value)
            ])
        );
    }

    /**
     * LSET key index value
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the length of the list.
     * Setting either the first or the last element of the list is O(1).
     *
     * @param string $key
     * @param int $index
     * @param string $value
     * @return bool
     */
    public function lset($key, $index, $value) {
        return $this->returnCommand(
            new Command('LSET', [
                new KeyParameter($key),
                new IntegerParameter($index),
                new StringParameter($value)
            ])
        );
    }

    /**
     * LTRIM key start stop
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of elements to be removed by the operation.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return bool
     */
    public function ltrim($key, $start, $stop) {
        return $this->returnCommand(
            new Command('LTRIM', [
                new KeyParameter($key),
                new IntegerParameter($start),
                new IntegerParameter($stop)
            ])
        );
    }

    /**
     * RPOP key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return string|null The value of the last element, or null when key does not exist.
     */
    public function rpop($key) {
        return $this->returnCommand(
            new Command('RPOP', new KeyParameter($key))
        );
    }

    /**
     * RPOPLPUSH source destination
     * Available since 1.2.0.
     * Time complexity: O(1)
     *
     * @param string $source
     * @param string $destination
     * @return string The element being popped and pushed.
     */
    public function rpoplpush($source, $destination) {
        return $this->returnCommand(
            new Command('RPOPLPUSH', [
                new KeyParameter($source),
                new KeyParameter($destination),
            ])
        );
    }

    /**
     * RPUSH key value [value ...]
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string|string[] $value
     * @return int The length of the list after the push operation.
     */
    public function rpush($key, $value) {
        return $this->returnCommand(
            new Command('RPUSH', [
                new KeyParameter($key),
                new StringsParameter($value)
            ])
        );
    }

    /**
     * RPUSHX key value
     * Available since 2.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string $value
     * @return int The length of the list after the push operation.
     */
    public function rpushx($key, $value) {
        return $this->returnCommand(
            new Command('RPUSHX', [
                new KeyParameter($key),
                new StringsParameter($value)
            ])
        );
    }

}
