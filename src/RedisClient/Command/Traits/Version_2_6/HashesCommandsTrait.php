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
use RedisClient\Command\Response\ResponseParser;

/**
 * trait HashesCommandsTrait
 * @link http://redis.io/commands#hash
 */
trait HashesCommandsTrait {

    /**
     * HDEL key field [field ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of fields to be removed.
     * @link http://redis.io/commands/hdel
     *
     * @param string $key
     * @param string|string[] $fields
     * @return int the number of fields that were removed from the hash,
     * not including specified but non existing fields.
     */
    public function hdel($key, $fields) {
        return $this->returnCommand(['HDEL'], [
            Parameter::key($key),
            Parameter::keys($fields),
        ]);
    }

    /**
     * HEXISTS key field
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hexists
     *
     * @param string $key
     * @param string $field
     * @return int 1 if the hash contains field. 0 if the hash does not contain field, or key does not exist.
     */
    public function hexists($key, $field) {
        return $this->returnCommand(['HEXISTS'], [
            Parameter::key($key),
            Parameter::key($field),
        ]);
    }

    /**
     * HGET key field
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hget
     *
     * @param string $key
     * @param string $field
     * @return string|null the value associated with field,
     * or nil when field is not present in the hash or key does not exist.
     */
    public function hget($key, $field) {
        return $this->returnCommand(['HGET'], [
            Parameter::key($key),
            Parameter::key($field),
        ]);
    }

    /**
     * HGETALL key
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the size of the hash.
     * @link http://redis.io/commands/hgetall
     *
     * @param string $key
     * @return array List of fields and their values stored in the hash,
     * or an empty list when key does not exist.
     */
    public function hgetall($key) {
        return $this->returnCommand(['HGETALL'], [Parameter::key($key)], ResponseParser::PARSE_ASSOC_ARRAY);
    }

    /**
     * HINCRBY key field increment
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hincrby
     *
     * @param string $key
     * @param string $field
     * @param int $increment
     * @return int The value at field after the increment operation.
     */
    public function hincrby($key, $field, $increment) {
        return $this->returnCommand(['HINCRBY'], [
            Parameter::key($key),
            Parameter::key($field),
            Parameter::integer($increment),
        ]);
    }

    /**
     * HINCRBYFLOAT key field increment
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hincrbyfloat
     *
     * @param string $key
     * @param string $field
     * @param float|int $increment
     * @return string The value of field after the increment.
     */
    public function hincrbyfloat($key, $field, $increment) {
        return $this->returnCommand(['HINCRBYFLOAT'], [
            Parameter::key($key),
            Parameter::key($field),
            Parameter::float($increment),
        ]);
    }

    /**
     * HKEYS key
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the size of the hash.
     * @link http://redis.io/commands/hkeys
     *
     * @param string $key
     * @return string[] List of fields in the hash, or an empty list when key does not exist.
     */
    public function hkeys($key) {
        return $this->returnCommand(['HKEYS'], [Parameter::key($key)]);
    }

    /**
     * HLEN key
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hlen
     *
     * @param string $key
     * @return int Number of fields in the hash, or 0 when key does not exist.
     */
    public function hlen($key) {
        return $this->returnCommand(['HLEN'], [Parameter::key($key)]);
    }

    /**
     * HMGET key field [field ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of fields being requested.
     * @link http://redis.io/commands/hmget
     *
     * @param string $key
     * @param string|string[] $fields
     * @return array List of values associated with the given fields, in the same order as they are requested.
     */
    public function hmget($key, $fields) {
        return $this->returnCommand(['HMGET'], [
            Parameter::key($key),
            Parameter::keys($fields),
        ]);
    }

    /**
     * HMSET key field value [field value ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of fields being set.
     * @link http://redis.io/commands/hmset
     *
     * @return bool True
     */
    public function hmset($key, array $fieldValues) {
        return $this->returnCommand(['HMSET'], [
            Parameter::key($key),
            Parameter::assocArray($fieldValues),
        ]);
    }

    /**
     * HSET key field value
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hset
     *
     * @param string $key
     * @param string $field
     * @param string $value
     * @return int 1 if field is a new field in the hash and value was set.
     * 0 if field already exists in the hash and the value was updated.
     */
    public function hset($key, $field, $value) {
        return $this->returnCommand(['HSET'], [
            Parameter::key($key),
            Parameter::key($field),
            Parameter::string($value),
        ]);
    }

    /**
     * HSETNX key field value
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hsetnx
     * @param string $key
     * @param string $field
     * @param string $value
     * @return int 1 if field is a new field in the hash and value was set.
     * 0 if field already exists in the hash and no operation was performed.
     */
    public function hsetnx($key, $field, $value) {
        return $this->returnCommand(['HSETNX'], [
            Parameter::key($key),
            Parameter::key($field),
            Parameter::string($value),
        ]);
    }

    /**
     * HVALS key
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the size of the hash.
     * @link http://redis.io/commands/hvals
     *
     * @param string $key
     * @return string[] List of values in the hash, or an empty list when key does not exist.
     */
    public function hvals($key) {
        return $this->returnCommand(['HVALS'], [Parameter::key($key)]);
    }

}
