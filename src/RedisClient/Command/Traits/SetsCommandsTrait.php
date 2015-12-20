<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

trait SetsCommandsTrait {

    /**
     * SADD key member [member ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of members to be added.
     *
     * @param string $key
     * @param string|string[] $member
     * @return int The number of elements that were added to the set,
     * not including all the elements already present into the set.
     */
    public function sadd($key, $member) {
        return $this->returnCommand(
            new Command('SADD', [
                Parameter::key($key),
                Parameter::keys($member)
            ])
        );
    }

    /**
     * SCARD key
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return int The cardinality (number of elements) of the set, or 0 if key does not exist.
     */
    public function scard($key) {
        return $this->returnCommand(
            new Command('SCARD', Parameter::key($key))
        );
    }

    /**
     * SDIFF key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     *
     * @param string|string[] $key
     * @return array List with members of the resulting set.
     */
    public function sdiff($key) {
        return $this->returnCommand(
            new Command('SDIFF', Parameter::keys($key))
        );
    }

    /**
     * SDIFFSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     *
     * @param string $destination
     * @param string|string[] $key
     * @return int The number of elements in the resulting set.
     */
    public function sdiffstore($destination, $key) {
        return $this->returnCommand(
            new Command('SDIFFSTORE', [
                Parameter::key($destination),
                Parameter::keys($key)
            ])
        );
    }

    /**
     * SINTER key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
     *
     * @param string|string[] $key
     * @return array List with members of the resulting set.
     */
    public function sinter($key) {
        return $this->returnCommand(
            new Command('SINTER', Parameter::keys($key))
        );
    }

    /**
     * SINTERSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
     *
     * @param string $destination
     * @param string|string[] $key
     * @return int The number of elements in the resulting set.
     */
    public function sinterstore($destination, $key) {
        return $this->returnCommand(
            new Command('SINTERSTORE', [
                Parameter::key($destination),
                Parameter::keys($key)
            ])
        );
    }

    /**
     * SISMEMBER key member
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string|string[] $member
     * @return int 1 if the element is a member of the set.
     * 0 if the element is not a member of the set, or if key does not exist.
     */
    public function sismember($key, $member) {
        return $this->returnCommand(
            new Command('SISMEMBER', [
                Parameter::key($key),
                Parameter::keys($member)
            ])
        );
    }

    /**
     * SMEMBERS key
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the set cardinality.
     *
     * @param string $key
     * @return string[] All elements of the set.
     */
    public function smembers($key) {
        return $this->returnCommand(
            new Command('SMEMBERS', Parameter::key($key))
        );
    }

    /**
     * SMOVE source destination member
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $source
     * @param string $destination
     * @param string $member
     * @return int 1 if the element is moved.
     * 0 if the element is not a member of source and no operation was performed.
     */
    public function smove($source, $destination, $member) {
        return $this->returnCommand(
            new Command('SMOVE', [
                Parameter::key($source),
                Parameter::key($destination),
                Parameter::key($member)
            ])
        );
    }

    /**
     * SPOP key [count]
     * Available since 1.0.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param int|null $count
     * @return string|null The removed element, or null when key does not exist.
     */
    public function spop($key, $count = null) {
        $params = [
            Parameter::key($key)
        ];
        if ($count) {
            $params[] = Parameter::integer($count);
        }
        return $this->returnCommand(
            new Command('SPOP', $params)
        );
    }

    /**
     * SRANDMEMBER key [count]
     * Available since 1.0.0.
     * Time complexity: Without the count argument O(1),
     * otherwise O(N) where N is the absolute value of the passed count.
     *
     * @param string $key
     * @param int|null $count
     * @return string|string[]
     */
    public function srandmember($key, $count = null) {
        $params = [
            Parameter::key($key)
        ];
        if ($count) {
            $params[] = Parameter::integer($count);
        }
        return $this->returnCommand(
            new Command('SRANDMEMBER', $params)
        );
    }

    /**
     * SREM key member [member ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of members to be removed.
     *
     * @param string $key
     * @param string|string[] $member
     * @return int The number of members that were removed from the set, not including non existing members.
     */
    public function srem($key, $member) {
        return $this->returnCommand(
            new Command('SREM', [
                Parameter::key($key),
                Parameter::keys($member),
            ])
        );
    }

    /**
     * SSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration,
     * including enough command calls for the cursor to return back to 0.
     * N is the number of elements inside the collection.
     *
     * @param string $key
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function sscan($key, $cursor, $pattern = null, $count = null) {
        $params = [
            Parameter::key($key),
            Parameter::integer($cursor),
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
            new Command('SSCAN', $params)
        );
    }

    /**
     * SUNION key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     *
     * @param string|string[] $key
     * @return string[] List with members of the resulting set.
     */
    public function sunion($key) {
        return $this->returnCommand(
            new Command('SUNION', Parameter::keys($key))
        );
    }

    /**
     * SUNIONSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     *
     * @param string $destination
     * @param string|string[] $key
     * @return int The number of elements in the resulting set.
     */
    public function sunionstore($destination, $key) {
        return $this->returnCommand(
            new Command('SUNIONSTORE', [
                Parameter::key($destination),
                Parameter::keys($key)
            ])
        );
    }

}
