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
use RedisClient\Command\Response\ResponseParser;

/**
 * SortedSets Commands
 * @link http://redis.io/commands#set
 * @link http://redis.io/topics/data-types#sorted-sets
 */
trait SortedSetsCommandsTrait {

    /**
     * ZADD key score member [score member ...]
     * Available since 1.2.0.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/zadd
     *
     * @param string $key
     * @param array $members array(member => score [, member => score ...])
     * @return int|string
     */
    public function zadd($key, array $members) {
        return $this->returnCommand(['ZADD'], $key, [$key, Parameter::assocArrayFlip($members)]);
    }

    /**
     * ZCARD key
     * Available since 1.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/zcard
     *
     * @param string $key
     * @return int The cardinality (number of elements) of the sorted set, or 0 if key does not exist.
     */
    public function zcard($key) {
        return $this->returnCommand(['ZCARD'], $key, [$key]);
    }

    /**
     * ZCOUNT key min max
     * Available since 2.0.0.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     * @link http://redis.io/commands/zcount
     *
     * @param int $key
     * @param int|string $min
     * @param int|string $max
     * @return int The number of elements in the specified score range.
     */
    public function zcount($key, $min, $max) {
        return $this->returnCommand(['ZCOUNT'], $key, [$key, $min, $max]);
    }

    /**
     * ZINCRBY key increment member
     * Available since 1.2.0.
     * Time complexity: O(log(N)) where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/zincrby
     *
     * @param string $key
     * @param int|float|string $increment
     * @param string $member
     * @return string The new score of member
     */
    public function zincrby($key, $increment, $member) {
        return $this->returnCommand(['ZINCRBY'], $key, [$key, $increment, $member]);
    }

    /**
     * ZINTERSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
     * Available since 2.0.0.
     * Time complexity: O(N*K)+O(M*log(M)) worst case with N being the smallest input sorted set,
     * K being the number of input sorted sets and M being the number of elements in the resulting sorted set.
     * @link http://redis.io/commands/zinterstore
     *
     * @param string $destination
     * @param string|string[] $keys
     * @param int|int[]|null $weights
     * @param string|null $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zinterstore($destination, $keys, $weights = null, $aggregate = null) {
        $keys = (array)$keys;
        $params = [$destination, count($keys), $keys];
        if ($weights) {
            $params[] = 'WEIGHTS';
            $params[] = (array) $weights;
        }
        if ($aggregate) {
            $params[] = 'AGGREGATE';
            $params[] = $aggregate;
        }
        return $this->returnCommand(['ZINTERSTORE'], $keys, $params);
    }

    /**
     * ZRANGE key start stop [WITHSCORES]
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements
     * in the sorted set and M the number of elements returned.
     * @link http://redis.io/commands/zrange
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @param bool|false $withscores
     * @return array List of elements in the specified range (optionally with their scores,
     * in case the WITHSCORES option is given).
     */
    public function zrange($key, $start, $stop, $withscores = false) {
        $params = [$key, $start, $stop];
        if ($withscores) {
            $params[] = 'WITHSCORES';
        }
        return $this->returnCommand(['ZRANGE'], $key, $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null);
    }

    /**
     * ZRANGEBYSCORE key min max [WITHSCORES] [LIMIT offset count]
     * Available since 1.0.5.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and
     * M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     * @link http://redis.io/commands/zrangebyscore
     *
     * @param string $key
     * @param string|int $min
     * @param string|int $max
     * @param bool|false $withscores
     * @param int|array|null $limit
     * @return string[]|array List of elements in the specified score range (optionally with their scores).
     */
    public function zrangebyscore($key, $min, $max, $withscores = false, $limit = null) {
        $params = [$key, $min, $max];
        if ($withscores) {
            $params[] = 'WITHSCORES';
        }
        if ($limit) {
            $params[] = 'LIMIT';
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(['ZRANGEBYSCORE'], $key, $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null);
    }

    /**
     * ZRANK key member
     * Available since 2.0.0.
     * Time complexity: O(log(N))
     * @link http://redis.io/commands/zrank
     *
     * @param string $key
     * @param string $member
     * @return int|null
     */
    public function zrank($key, $member) {
        return $this->returnCommand(['ZRANK'], $key, [$key, $member]);
    }

    /**
     * ZREM key member [member ...]
     * Available since 1.2.0.
     * Time complexity: O(M*log(N)) with N being the number of elements in the sorted set
     * and M the number of elements to be removed.
     * @link http://redis.io/commands/zrem
     *
     * @param string $key
     * @param string|string[] $members
     * @return int The number of members removed from the sorted set, not including non existing members.
     */
    public function zrem($key, $members) {
        return $this->returnCommand(['ZREM'], $key, [$key, (array) $members]);
    }

    /**
     * ZREMRANGEBYRANK key start stop
     * Available since 2.0.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     * @link http://redis.io/commands/zremrangebyrank
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return int The number of elements removed.
     */
    public function zremrangebyrank($key, $start, $stop) {
        return $this->returnCommand(['ZREMRANGEBYRANK'], $key, [$key, $start, $stop]);
    }

    /**
     * ZREMRANGEBYSCORE key min max
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     * @link http://redis.io/commands/zremrangebyscore
     *
     * @param string $key
     * @param string|int $min
     * @param string|int $max
     * @return int The number of elements removed.
     */
    public function zremrangebyscore($key, $min, $max) {
        return $this->returnCommand(['ZREMRANGEBYSCORE'], $key, [$key, $min, $max]);
    }

    /**
     *  ZREVRANGE key start stop [WITHSCORES]
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements returned.
     * @link http://redis.io/commands/zrevrange
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @param bool|false $withscores
     * @return array List of elements in the specified range (optionally with their scores,
     * in case the WITHSCORES option is given).
     */
    public function zrevrange($key, $start, $stop, $withscores = false) {
        $params = [$key, $start, $stop];
        if ($withscores) {
            $params[] = 'WITHSCORES';
        }
        return $this->returnCommand(['ZREVRANGE'], $key, $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null);
    }

    /**
     * ZREVRANGEBYSCORE key max min [WITHSCORES] [LIMIT offset count]
     * Available since 2.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     * @link http://redis.io/commands/zrevrangebyscore
     *
     * @param string $key
     * @param string $max
     * @param string $min
     * @param bool|false $withscores
     * @param string|array|null $limit
     * @return string[]|array list of elements in the specified score range (optionally with their scores).
     */
    public function zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null) {
        $params = [$key, $max, $min];
        if ($withscores) {
            $params [] = 'WITHSCORES';
        }
        if ($limit) {
            $params[] = 'LIMIT';
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(['ZREVRANGEBYSCORE'], $key, $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null);
    }

    /**
     * ZREVRANK key member
     * Available since 2.0.0.
     * Time complexity: O(log(N))
     * @link http://redis.io/commands/zrevrank
     *
     * @param string $key
     * @param string $member
     * @return int|null
     */
    public function zrevrank($key, $member) {
        return $this->returnCommand(['ZREVRANK'], $key, [$key, $member]);
    }

    /**
     * ZSCORE key member
     * Available since 1.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/zscore
     *
     * @param string $key
     * @param string $member
     * @return int The score of member (a double precision floating point number), represented as string.
     */
    public function zscore($key, $member) {
        return $this->returnCommand(['ZSCORE'], $key, [$key, $member]);
    }

    /**
     * ZUNIONSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
     * Available since 2.0.0.
     * Time complexity: O(N)+O(M log(M)) with N being the sum of the sizes of the input sorted sets,
     * and M being the number of elements in the resulting sorted set.
     * @link http://redis.io/commands/zunionstore
     *
     * @param string $destination
     * @param string|string[] $keys
     * @param int|int[] $weights
     * @param string $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zunionstore($destination, $keys, $weights = null, $aggregate = null) {
        $keys = (array)$keys;
        $params = [$destination, count($keys), $keys];
        if ($weights) {
            $params[] = 'WEIGHTS';
            $params[] = (array) $weights;
        }
        if ($aggregate) {
            $params[] = 'AGGREGATE';
            $params[] = $aggregate;
        }
        return $this->returnCommand(['ZUNIONSTORE'], $keys, $params);
    }

}
