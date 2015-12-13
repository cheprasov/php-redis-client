<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\AggregateParameter;
use RedisClient\Command\Parameter\AssocArrayParameter;
use RedisClient\Command\Parameter\IntegerParameter;
use RedisClient\Command\Parameter\IntegersParameter;
use RedisClient\Command\Parameter\KeyParameter;
use RedisClient\Command\Parameter\KeysParameter;
use RedisClient\Command\Parameter\LimitParameter;
use RedisClient\Command\Parameter\MinMaxParameter;
use RedisClient\Command\Parameter\NXOrXXParameter;
use RedisClient\Command\Parameter\SpecifyIntervalParameter;
use RedisClient\Command\Parameter\StringParameter;
use RedisClient\Command\Parameter\StringsParameter;
use RedisClient\Command\Response\AssocArrayResponseParser;

trait RedisSetsCommandsTrait {

    /**
     * ZADD key [NX|XX] [CH] [INCR] score member [score member ...]
     * Available since 1.2.0.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     *
     * @param string $key
     * @param string|null $nx NX or XX
     * @param bool|false $ch
     * @param bool|false $incr
     * @param array $member array(member => score [, member => score ...])
     * @return int|string
     */
    public function zadd($key, $nx = null, $ch = false, $incr = false, array $member) {
        $params = [
            new KeyParameter($key),
        ];
        if ($nx) {
            $params[] = new NXOrXXParameter($nx);
        }
        if ($ch) {
            $params[] = new StringParameter('CH');
        }
        if ($incr) {
            $params[] = new StringParameter('INCR');
        }
        $params[] = new AssocArrayParameter($member);
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
            new Command('ZCARD', new KeyParameter($key))
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
                new KeyParameter($key),
                new MinMaxParameter($min),
                new MinMaxParameter($max),
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
                new KeyParameter($key),
                new IntegerParameter($increment),
                new KeyParameter($member)
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
     * @param string|string[] $key
     * @param string|string[] null $weight
     * @param string|null $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zinterstore($destination, $key, $weight = null, $aggregate = null) {
        $params = [
            new KeyParameter($destination),
            new KeysParameter($key),
        ];
        if ($weight) {
            $params[] = new StringParameter('WEIGHTS');
            $params[] = new IntegersParameter($weight);
        }
        if ($aggregate) {
            $params[] = new StringParameter('AGGREGATE');
            $params[] = new AggregateParameter($aggregate);
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
                new KeyParameter($key),
                new SpecifyIntervalParameter($min),
                new SpecifyIntervalParameter($max),
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
            new KeyParameter($key),
            new IntegerParameter($start),
            new IntegerParameter($stop),
        ];
        if ($withscores) {
            $params[] = new StringParameter('WITHSCORES');
        }
        return $this->returnCommand(
            new Command('ZRANGE', $params, $withscores ? AssocArrayResponseParser::getInstance() : null)
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
            new KeyParameter($key),
            new SpecifyIntervalParameter($min),
            new SpecifyIntervalParameter($max),
        ];
        if ($limit) {
            $params[] = new StringParameter('LIMIT');
            $params[] = new LimitParameter($limit);
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
     * @param boolean|false $withscores
     * @param int|array|null $limit
     * @return string[]|array List of elements in the specified score range (optionally with their scores).
     */
    public function zrangebyscore($key, $min, $max, $withscores = false, $limit = null) {
        $params = [
            new KeyParameter($key),
            new MinMaxParameter($min),
            new MinMaxParameter($max),
        ];
        if ($withscores) {
            $params [] = new StringParameter('WITHSCORES');
        }
        if ($limit) {
            $params[] = new StringParameter('LIMIT');
            $params[] = new LimitParameter($limit);
        }
        return $this->returnCommand(
            new Command('ZRANGEBYSCORE', $params, $withscores ? AssocArrayResponseParser::getInstance() : null)
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
                new KeyParameter($key),
                new KeyParameter($member)
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
                new KeyParameter($key),
                new KeysParameter($member)
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
                new KeyParameter($key),
                new SpecifyIntervalParameter($min),
                new SpecifyIntervalParameter($max),
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
                new KeyParameter($key),
                new IntegerParameter($start),
                new IntegerParameter($stop)
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
                new KeyParameter($key),
                new MinMaxParameter($min),
                new MinMaxParameter($max),
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
            new KeyParameter($key),
            new IntegerParameter($start),
            new IntegerParameter($stop),
        ];
        if ($withscores) {
            $params[] = new StringParameter('WITHSCORES');
        }
        return $this->returnCommand(
            new Command('ZREVRANGE', $params, $withscores ? AssocArrayResponseParser::getInstance() : null)
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
            new KeyParameter($key),
            new SpecifyIntervalParameter($max),
            new SpecifyIntervalParameter($min),
        ];
        if ($limit) {
            $params[] = new StringParameter('LIMIT');
            $params[] = new LimitParameter($limit);
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
                new KeyParameter($key),
                new KeyParameter($member)
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
            new KeyParameter($key),
            new IntegerParameter($cursor),
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
                new KeyParameter($key),
                new KeyParameter($this)
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
     * @param string|string[] $key
     * @param int|int[] $weight
     * @param string $aggregate
     * @return int The number of elements in the resulting sorted set at destination.
     */
    public function zunionstore($destination, $key, $weight, $aggregate) {
        $params = [
            new KeyParameter($destination),
            new KeysParameter($key),
        ];
        if ($weight) {
            $params[] = new StringParameter('WEIGHTS');
            $params[] = new IntegersParameter($weight);
        }
        if ($aggregate) {
            $params[] = new StringParameter('AGGREGATE');
            $params[] = new AggregateParameter($aggregate);
        }
        return $this->returnCommand(
            new Command('ZUNIONSTORE', $params)
        );
    }

}
