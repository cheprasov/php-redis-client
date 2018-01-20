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
namespace RedisClient\Command\Traits\Version2x8;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version2x6\SortedSetsCommandsTrait as SortedSetsCommandsTraitVersion2x6;

/**
 * SortedSets Commands
 * @link http://redis.io/commands#set
 * @link http://redis.io/topics/data-types#sorted-sets
 */
trait SortedSetsCommandsTrait {

    use SortedSetsCommandsTraitVersion2x6;

    /**
     * ZLEXCOUNT key min max
     * Available since 2.8.9.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     * @link http://redis.io/commands/zlexcount
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @return int The number of elements in the specified score range.
     */
    public function zlexcount($key, $min, $max) {
        return $this->returnCommand(['ZLEXCOUNT'], $key, [$key, $min, $max]);
    }

    /**
     * ZRANGEBYLEX key min max [LIMIT offset count]
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and
     * M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     * @link http://redis.io/commands/zrangebylex
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @param int|array $limit
     * @return string[] List of elements in the specified score range.
     */
    public function zrangebylex($key, $min, $max, $limit = null) {
        $params = [$key, $min, $max];
        if ($limit) {
            $params[] = 'LIMIT';
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(['ZRANGEBYLEX'], $key, $params);
    }

    /**
     * ZREMRANGEBYLEX key min max
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     * @link http://redis.io/commands/zremrangebylex
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @return int The number of elements removed.
     */
    public function zremrangebylex($key, $min, $max) {
        return $this->returnCommand(['ZREMRANGEBYLEX'], $key, [$key, $min, $max]);
    }

    /**
     * ZREVRANGEBYLEX key max min [LIMIT offset count]
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     * @link http://redis.io/commands/zrevrangebylex
     *
     * @param string $key
     * @param string $max
     * @param string $min
     * @param int|array $limit
     * @return string[] List of elements in the specified score range.
     */
    public function zrevrangebylex($key, $max, $min, $limit = null) {
        $params = [$key, $max, $min];
        if ($limit) {
            $params[] = 'LIMIT';
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(['ZREVRANGEBYLEX'], $key, $params);
    }

    /**
     * ZSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration,
     * including enough command calls for the cursor to return back to 0.
     * N is the number of elements inside the collection.
     * @link http://redis.io/commands/zscan
     *
     * @param string $key
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function zscan($key, $cursor, $pattern = null, $count = null) {
        $params = [$key, $cursor];
        if ($pattern) {
            $params[] = 'MATCH';
            $params[] = $pattern;
        }
        if ($count) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        return $this->returnCommand(['ZSCAN'], $key, $params);
    }

}
