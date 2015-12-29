<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Response\ResponseParser;

trait SortedSetsCommandsTrait {

    /**
     * ZADD key [NX|XX] [CH] [INCR] score member [score member ...]
     * Available since 1.2.0.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     *
     * @param string $key
     * @param array $member array(member => score [, member => score ...])
     * @param string|null $nx NX or XX
     * @param bool|false $ch
     * @param bool|false $incr
     * @return int|string
     */
    public function zadd($key, array $member, $nx = null, $ch = false, $incr = false) {
        $params = [
            Parameter::key($key),
        ];
        if ($nx) {
            $params[] = Parameter::nxXx($nx);
        }
        if ($ch) {
            $params[] = Parameter::string('CH');
        }
        if ($incr) {
            $params[] = Parameter::string('INCR');
        }
        $params[] = Parameter::assocArray($member, true);
        return $this->returnCommand(
            new Command('ZADD', $params)
        );
    }

    /**
     * ZCARD key
     * Available since 1.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @return int The cardinality (number of elements) of the sorted set, or 0 if key does not exist.
     */
    public function zcard($key) {
        return $this->returnCommand(
            new Command('ZCARD', Parameter::key($key))
        );
    }

    /**
     * ZCOUNT key min max
     * Available since 2.0.0.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     *
     * @param int $key
     * @param int|string $min
     * @param int|string $max
     * @return int The number of elements in the specified score range.
     */
    public function zcount($key, $min, $max) {
        return $this->returnCommand(
            new Command('ZCOUNT', [
                Parameter::key($key),
                Parameter::minMax($min),
                Parameter::minMax($max),
            ])
        );
    }

    /**
     * ZINCRBY key increment member
     * Available since 1.2.0.
     * Time complexity: O(log(N)) where N is the number of elements in the sorted set.
     *
     * @param string $key
     * @param int $increment
     * @param string $member
     * @return int The new score of member
     */
    public function zincrby($key, $increment, $member) {
        return $this->returnCommand(
            new Command('ZINCRBY', [
                Parameter::key($key),
                Parameter::integer($increment),
                Parameter::key($member)
            ], function($response) {
                return (int) $response;
            })
        );
    }

    /**
     * ZINTERSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
     * Available since 2.0.0.
     * Time complexity: O(N*K)+O(M*log(M)) worst case with N being the smallest input sorted set,
     * K being the number of input sorted sets and M being the number of elements in the resulting sorted set.
     *
     * @param string $destination
     * @param string|string[] $keys
     * @param int|int[] null $weight
     * @param string|null $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zinterstore($destination, $keys, $weights = null, $aggregate = null) {
        $params = [
            Parameter::key($destination),
            Parameter::keys($keys),
        ];
        if ($weights) {
            $params[] = Parameter::string('WEIGHTS');
            $params[] = Parameter::integers($weights);
        }
        if ($aggregate) {
            $params[] = Parameter::string('AGGREGATE');
            $params[] = Parameter::aggregate($aggregate);
        }
        return $this->returnCommand(
            new Command('ZINTERSTORE', $params)
        );
    }

    /**
     * ZLEXCOUNT key min max
     * Available since 2.8.9.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @return int The number of elements in the specified score range.
     */
    public function zlexcount($key, $min, $max) {
        return $this->returnCommand(
            new Command('ZLEXCOUNT', [
                Parameter::key($key),
                Parameter::specifyInterval($min),
                Parameter::specifyInterval($max),
            ])
        );
    }

    /**
     * ZRANGE key start stop [WITHSCORES]
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements
     * in the sorted set and M the number of elements returned.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @param bool|false $withscores
     * @return array List of elements in the specified range (optionally with their scores,
     * in case the WITHSCORES option is given).
     */
    public function zrange($key, $start, $stop, $withscores = false) {
        $params = [
            Parameter::key($key),
            Parameter::integer($start),
            Parameter::integer($stop),
        ];
        if ($withscores) {
            $params[] = Parameter::string('WITHSCORES');
        }
        return $this->returnCommand(
            new Command('ZRANGE', $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null)
        );
    }

    /**
     * ZRANGEBYLEX key min max [LIMIT offset count]
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and
     * M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @param int|array $limit
     * @return string[] List of elements in the specified score range.
     */
    public function zrangebylex($key, $min, $max, $limit = null) {
        $params = [
            Parameter::key($key),
            Parameter::specifyInterval($min),
            Parameter::specifyInterval($max),
        ];
        if ($limit) {
            $params[] = Parameter::string('LIMIT');
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(
            new Command('ZRANGEBYLEX', $params)
        );
    }

    /**
     * ZRANGEBYSCORE key min max [WITHSCORES] [LIMIT offset count]
     * Available since 1.0.5.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set and
     * M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     *
     * @param string $key
     * @param string|int $min
     * @param string|int $max
     * @param bool|false $withscores
     * @param int|array|null $limit
     * @return string[]|array List of elements in the specified score range (optionally with their scores).
     */
    public function zrangebyscore($key, $min, $max, $withscores = false, $limit = null) {
        $params = [
            Parameter::key($key),
            Parameter::minMax($min),
            Parameter::minMax($max),
        ];
        if ($withscores) {
            $params [] = Parameter::string('WITHSCORES');
        }
        if ($limit) {
            $params[] = Parameter::string('LIMIT');
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(
            new Command('ZRANGEBYSCORE', $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null)
        );
    }

    /**
     * ZRANK key member
     * Available since 2.0.0.
     * Time complexity: O(log(N))
     *
     * @param string $key
     * @param string $member
     * @return int|null
     */
    public function zrank($key, $member) {
        return $this->returnCommand(
            new Command('ZRANK', [
                Parameter::key($key),
                Parameter::key($member)
            ])
        );
    }

    /**
     * ZREM key member [member ...]
     * Available since 1.2.0.
     * Time complexity: O(M*log(N)) with N being the number of elements in the sorted set
     * and M the number of elements to be removed.
     *
     * @param string $key
     * @param string|string[] $member
     * @return int The number of members removed from the sorted set, not including non existing members.
     */
    public function zrem($key, $member) {
        return $this->returnCommand(
            new Command('ZREM', [
                Parameter::key($key),
                Parameter::keys($member)
            ])
        );
    }

    /**
     * ZREMRANGEBYLEX key min max
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     *
     * @param string $key
     * @param string $min
     * @param string $max
     * @return int The number of elements removed.
     */
    public function zremrangebylex($key, $min, $max) {
        return $this->returnCommand(
            new Command('ZREMRANGEBYLEX', [
                Parameter::key($key),
                Parameter::specifyInterval($min),
                Parameter::specifyInterval($max),
            ])
        );
    }

    /**
     * ZREMRANGEBYRANK key start stop
     * Available since 2.0.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return int The number of elements removed.
     */
    public function zremrangebyrank($key, $start, $stop) {
        return $this->returnCommand(
            new Command('ZREMRANGEBYRANK', [
                Parameter::key($key),
                Parameter::integer($start),
                Parameter::integer($stop)
            ])
        );
    }

    /**
     * ZREMRANGEBYSCORE key min max
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements removed by the operation.
     *
     * @param string $key
     * @param string|int $min
     * @param string|int $max
     * @return int The number of elements removed.
     */
    public function zremrangebyscore($key, $min, $max) {
        return $this->returnCommand(
            new Command('ZREMRANGEBYSCORE', [
                Parameter::key($key),
                Parameter::minMax($min),
                Parameter::minMax($max),
            ])
        );
    }

    /**
     *  ZREVRANGE key start stop [WITHSCORES]
     * Available since 1.2.0.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements returned.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @param bool|false $withscores
     * @return array List of elements in the specified range (optionally with their scores,
     * in case the WITHSCORES option is given).
     */
    public function zrevrange($key, $start, $stop, $withscores = false) {
        $params = [
            Parameter::key($key),
            Parameter::integer($start),
            Parameter::integer($stop),
        ];
        if ($withscores) {
            $params[] = Parameter::string('WITHSCORES');
        }
        return $this->returnCommand(
            new Command('ZREVRANGE', $params, $withscores ? ResponseParser::PARSE_ASSOC_ARRAY : null)
        );
    }

    /**
     * ZREVRANGEBYLEX key max min [LIMIT offset count]
     * Available since 2.8.9.
     * Time complexity: O(log(N)+M) with N being the number of elements in the sorted set
     * and M the number of elements being returned.
     * If M is constant (e.g. always asking for the first 10 elements with LIMIT), you can consider it O(log(N)).
     *
     * @param string $key
     * @param string $max
     * @param string $min
     * @param int|array $limit
     * @return string[] List of elements in the specified score range.
     */
    public function zrevrangebylex($key, $max, $min, $limit = null) {
        $params = [
            Parameter::key($key),
            Parameter::specifyInterval($max),
            Parameter::specifyInterval($min),
        ];
        if ($limit) {
            $params[] = Parameter::string('LIMIT');
            $params[] = Parameter::limit($limit);
        }
        return $this->returnCommand(
            new Command('ZREVRANGEBYLEX', $params)
        );
    }

    /**
     * ZREVRANK key member
     * Available since 2.0.0.
     * Time complexity: O(log(N))
     *
     * @param string $key
     * @param string $member
     * @return int|null
     */
    public function zrevrank($key, $member) {
        return $this->returnCommand(
            new Command('ZREVRANK', [
                Parameter::key($key),
                Parameter::key($member)
            ])
        );
    }

    /**
     * ZSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration,
     * including enough command calls for the cursor to return back to 0.
     * N is the number of elements inside the collection..
     *
     * @param string $key
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function zscan($key, $cursor, $pattern = null, $count = null) {
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
            new Command('ZSCAN', $params)
        );
    }

    /**
     * ZSCORE key member
     * Available since 1.2.0.
     * Time complexity: O(1)
     *
     * @param string $key
     * @param string $member
     * @return int The score of member (a double precision floating point number), represented as string.
     */
    public function zscore($key, $member) {
        return $this->returnCommand(
            new Command('ZSCORE', [
                Parameter::key($key),
                Parameter::key($this)
            ])
        );
    }

    /**
     * ZUNIONSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
     * Available since 2.0.0.
     * Time complexity: O(N)+O(M log(M)) with N being the sum of the sizes of the input sorted sets,
     * and M being the number of elements in the resulting sorted set.
     *
     * @param string $destination
     * @param string|string[] $keys
     * @param int|int[] $weights
     * @param string $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zunionstore($destination, $keys, $weights, $aggregate) {
        $params = [
            Parameter::key($destination),
            Parameter::keys($keys),
        ];
        if ($weights) {
            $params[] = Parameter::string('WEIGHTS');
            $params[] = Parameter::integers($weights);
        }
        if ($aggregate) {
            $params[] = Parameter::string('AGGREGATE');
            $params[] = Parameter::aggregate($aggregate);
        }
        return $this->returnCommand(
            new Command('ZUNIONSTORE', $params)
        );
    }

}
