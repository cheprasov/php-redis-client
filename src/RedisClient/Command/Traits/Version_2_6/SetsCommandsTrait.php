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
namespace RedisClient\Command\Traits\Version_2_6;

use RedisClient\Command\Parameter\Parameter;

trait SetsCommandsTrait {

    /**
     * SADD key member [member ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of members to be added.
     * @link http://redis.io/commands/sadd
     *
     * @param string $key
     * @param string|string[] $members
     * @return int The number of elements that were added to the set,
     * not including all the elements already present into the set.
     */
    public function sadd($key, $members) {
        return $this->returnCommand(['SADD'], [
            Parameter::key($key),
            Parameter::keys($members)
        ]);
    }

    /**
     * SCARD key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/scard
     *
     * @param string $key
     * @return int The cardinality (number of elements) of the set, or 0 if key does not exist.
     */
    public function scard($key) {
        return $this->returnCommand(['SCARD'], [Parameter::key($key)]);
    }

    /**
     * SDIFF key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     * @link http://redis.io/commands/sdiff
     *
     * @param string|string[] $keys
     * @return array List with members of the resulting set.
     */
    public function sdiff($keys) {
        return $this->returnCommand(['SDIFF'], Parameter::keys($keys));
    }

    /**
     * SDIFFSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     * @link http://redis.io/commands/sdiffstore
     *
     * @param string $destination
     * @param string|string[] $keys
     * @return int The number of elements in the resulting set.
     */
    public function sdiffstore($destination, $keys) {
        return $this->returnCommand(['SDIFFSTORE'], [
            Parameter::key($destination),
            Parameter::keys($keys)
        ]);
    }

    /**
     * SINTER key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
     * @link http://redis.io/commands/sinter
     *
     * @param string|string[] $keys
     * @return array List with members of the resulting set.
     */
    public function sinter($keys) {
        return $this->returnCommand(['SINTER'], Parameter::keys($keys));
    }

    /**
     * SINTERSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N*M) worst case where N is the cardinality of the smallest set and M is the number of sets.
     * @link http://redis.io/commands/sinterstore
     *
     * @param string $destination
     * @param string|string[] $keys
     * @return int The number of elements in the resulting set.
     */
    public function sinterstore($destination, $keys) {
        return $this->returnCommand(['SINTERSTORE'], [
            Parameter::key($destination),
            Parameter::keys($keys)
        ]);
    }

    /**
     * SISMEMBER key member
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/sismember
     *
     * @param string $key
     * @param string $member
     * @return int 1 if the element is a member of the set.
     * 0 if the element is not a member of the set, or if key does not exist.
     */
    public function sismember($key, $member) {
        return $this->returnCommand(['SISMEMBER'], [
            Parameter::key($key),
            Parameter::key($member)
        ]);
    }

    /**
     * SMEMBERS key
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the set cardinality.
     * @link http://redis.io/commands/smembers
     *
     * @param string $key
     * @return string[] All elements of the set.
     */
    public function smembers($key) {
        return $this->returnCommand(['SMEMBERS'], [Parameter::key($key)]);
    }

    /**
     * SMOVE source destination member
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/smove
     *
     * @param string $source
     * @param string $destination
     * @param string $member
     * @return int 1 if the element is moved.
     * 0 if the element is not a member of source and no operation was performed.
     */
    public function smove($source, $destination, $member) {
        return $this->returnCommand(['SMOVE'], [
            Parameter::key($source),
            Parameter::key($destination),
            Parameter::key($member)
        ]);
    }

    /**
     * SPOP key
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/spop
     *
     * @param string $key
     * @return string|null The removed element, or null when key does not exist.
     */
    public function spop($key) {
        return $this->returnCommand(['SPOP'], [Parameter::key($key)]);
    }

    /**
     * SRANDMEMBER key [count]
     * Available since 1.0.0.
     * Time complexity: Without the count argument O(1),
     * otherwise O(N) where N is the absolute value of the passed count.
     * @link http://redis.io/commands/srandmember
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
        return $this->returnCommand(['SRANDMEMBER'], $params);
    }

    /**
     * SREM key member [member ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the number of members to be removed.
     * @link http://redis.io/commands/srem
     *
     * @param string $key
     * @param string|string[] $members
     * @return int The number of members that were removed from the set, not including non existing members.
     */
    public function srem($key, $members) {
        return $this->returnCommand(['SREM'], [
            Parameter::key($key),
            Parameter::keys($members),
        ]);
    }

    /**
     * SUNION key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     * @link http://redis.io/commands/sunion
     *
     * @param string|string[] $keys
     * @return string[] List with members of the resulting set.
     */
    public function sunion($keys) {
        return $this->returnCommand(['SUNION'], Parameter::keys($keys));
    }

    /**
     * SUNIONSTORE destination key [key ...]
     * Available since 1.0.0.
     * Time complexity: O(N) where N is the total number of elements in all given sets.
     * @link http://redis.io/commands/sunionstore
     *
     * @param string $destination
     * @param string|string[] $keys
     * @return int The number of elements in the resulting set.
     */
    public function sunionstore($destination, $keys) {
        return $this->returnCommand(['SUNIONSTORE'], [
            Parameter::key($destination),
            Parameter::keys($keys)
        ]);
    }

}
