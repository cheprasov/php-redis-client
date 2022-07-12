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
namespace RedisClient\Command\Traits\Version5x0;

use RedisClient\Command\Parameter\Parameter;

/**
 * Streams Commands
 * @link https://redis.io/commands#stream
 * @link https://redis.io/topics/streams-intro
 */
trait StreamsCommandsTrait {

    /**
     * XACK key group ID [ID ...]
     * Available since 5.0.0.
     * Time complexity: O(1) for each message ID processed.
     * @link https://redis.io/commands/xack
     *
     * @param string $key
     * @param string $group
     * @param string|string[] $ids
     * @return int
     */
    public function xack($key, $group, $ids) {
        return $this->returnCommand(['XACK'], [$key], [$key, $group, (array)$ids]);
    }

    /**
     * XADD key ID [MAXLEN ~ maxlen] field string [field string ...]
     * Available since 5.0.0.
     * Time complexity: O(1)
     * @link https://redis.io/commands/xadd
     *
     * @param string $key
     * @param string $id
     * @param array $fieldStrings
     * @param null|int $maxlen
     * @return string
     */
    public function xadd($key, $id, $fieldStrings, $maxlen = null) {
        $params = [$key, $id];
        if (isset($maxlen)) {
            $params[] = ['MAXLEN', '~', $maxlen];
        }
        $params[] = Parameter::assocArray($fieldStrings);
        return $this->returnCommand(['XADD'], [$key], $params);
    }

    /**
     * XCLAIM key group consumer min-idle-time ID [ID ...] [IDLE ms] [TIME ms-unix-time] [RETRYCOUNT count] [FORCE] [JUSTID]
     * Available since 5.0.0.
     * Time complexity: O(log N) with N being the number of messages in the PEL of the consumer group.
     * @link https://redis.io/commands/xclaim
     *
     * @param string $key
     * @param string $group
     * @param string $consumer
     * @param int $minIdleTime
     * @param string|string[] $ids
     * @param int|null $idle
     * @param int|null $time
     * @param int|null $retrycount
     * @param bool $force
     * @param bool $justid
     * @return array
     */
    public function xclaim($key, $group, $consumer, $minIdleTime, $ids, $idle = null, $time = null, $retrycount = null, $force = false, $justid = false) {
        $params = [$key, $group, $consumer, $minIdleTime, (array)$ids];
        if (isset($idle)) {
            $params[] = ['IDLE', $idle];
        }
        if (isset($time)) {
            $params[] = ['TIME', $time];
        }
        if (isset($retrycount)) {
            $params[] = ['RETRYCOUNT', $retrycount];
        }
        if ($force) {
            $params[] = 'FORCE';
        }
        if ($justid) {
            $params[] = 'JUSTID';
        }
        return $this->returnCommand(['XCLAIM'], [$key], $params);
    }

    /**
     * XDEL key ID [ID ...]
     * Available since 5.0.0.
     * Time complexity: O(1) for each single item to delete in the stream, regardless of the stream size.
     * @link https://redis.io/commands/xdel
     *
     * @param string $key
     * @param string|string[] $ids
     * @return int The number of entries actually deleted.
     */
    public function xdel($key, $ids) {
        return $this->returnCommand(['XDEL'], [$key], [$key, (array)$ids]);
    }

    /**
     * XGROUP CREATE key groupname id-or-$
     * @link https://redis.io/commands/xgroup
     *
     * @param string $key
     * @param string $groupname
     * @param string $id
     * @return
     */
    public function xgroupCreate($key, $groupname, $id) {
        return $this->returnCommand(['XGROUP'], [$key], ['CREATE', $key, $groupname, $id]);
    }

    /**
     * XGROUP SETID key groupname id-or-$
     * @link https://redis.io/commands/xgroup
     *
     * @param string $key
     * @param string $groupname
     * @param string $id
     * @return
     */
    public function xgroupSetid($key, $groupname, $id) {
        return $this->returnCommand(['XGROUP'], [$key], ['SETID', $key, $groupname, $id]);
    }

    /**
     * XGROUP DESTROY key groupname
     * @link https://redis.io/commands/xgroup
     *
     * @param string $key
     * @param string $groupname
     * @return
     */
    public function xgroupDestroy($key, $groupname) {
        return $this->returnCommand(['XGROUP'], [$key], ['DESTROY', $key, $groupname]);
    }

    /**
     * XGROUP DELCONSUMER key groupname consumername
     * @link https://redis.io/commands/xgroup
     *
     * @param string $key
     * @param string $groupname
     * @param string $consumername
     * @return
     */
    public function xgroupDelconsumer($key, $groupname, $consumername) {
        return $this->returnCommand(['XGROUP'], [$key], ['DELCONSUMER', $key, $groupname, $consumername]);
    }

    /**
     * XGROUP HELP
     * @link https://redis.io/commands/xgroup
     *
     * @return string
     */
    public function xgroupHelp() {
        return $this->returnCommand(['XGROUP'], null, ['HELP']);
    }

    /**
     * XINFO [CONSUMERS key groupname] [GROUPS key] [STREAM key] [HELP]
     * Available since 5.0.0.
     * Time complexity: O(N) with N being the number of returned items for the subcommands CONSUMERS and GROUPS.
     * The STREAM subcommand is O(log N) with N being the number of items in the stream.
     * @link https://redis.io/commands/xinfo
     *
     * @param string[]|null $consumers [key, groupname]
     * @param string|null $groupsKey
     * @param string|null $streamKey
     * @param bool $help
     * @return array
     */
    public function xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $help = false) {
        $params = [];
        if (isset($consumersKey, $consumersGroup)) {
            $params[] = ['CONSUMERS', $consumersKey, $consumersGroup];
        }
        if (isset($groupsKey)) {
            $params[] = ['GROUPS', $groupsKey];
        }
        if (isset($streamKey)) {
            $params[] = ['STREAM', $streamKey];
        }
        if ($help) {
            $params[] = 'HELP';
        }
        return $this->returnCommand(['XINFO'], null, $params);
    }

    /**
     * XLEN key
     * Available since 5.0.0.
     * Time complexity: O(1)
     * @link https://redis.io/commands/xlen
     *
     * @return int The number of entries of the stream at key.
     */
    public function xlen($key) {
        return $this->returnCommand(['XLEN'], [$key], [$key]);
    }

    /**
     * XPENDING key group [start end count] [consumer]
     * Available since 5.0.0.
     * Time complexity: O(N) with N being the number of elements returned, so asking for a small fixed number
     * of entries per call is O(1). When the command returns just the summary
     * it runs in O(1) time assuming the list of consumers is small,
     * otherwise there is additional O(N) time needed to iterate every consumer.
     * @link https://redis.io/commands/xpending
     *
     * @param string $key
     * @param string $group
     * @param string|null $start
     * @param string|null $end
     * @param string|null $count
     * @param string|null $consumer
     * @return array
     */
    public function xpending($key, $group, $start = null, $end = null, $count = null, $consumer = null) {
        $params = [$key, $group];
        if (isset($start, $end, $count)) {
            $params[] = [$start, $end, $count];
        }
        if (isset($consumer)) {
            $params[] = $consumer;
        }
        return $this->returnCommand(['XPENDING'], [$key], $params);
    }

    /**
     * XRANGE key start end [COUNT count]
     * Available since 5.0.0.
     * Time complexity: O(N) with N being the number of elements being returned.
     * If N is constant (e.g. always asking for the first 10 elements with COUNT), you can consider it O(1).
     * @link https://redis.io/commands/xrange
     *
     * @param string $key
     * @param string $start
     * @param string $end
     * @param string|null $count
     * @return array
     */
    public function xrange($key, $start, $end, $count = null) {
        $params = [$key, $start, $end];
        if (isset($count)) {
            $params[] = ['COUNT', $count];
        }
        return $this->returnCommand(['XRANGE'], [$key], $params);
    }

    /**
     * XREAD [COUNT count] [BLOCK milliseconds] STREAMS key [key ...] ID [ID ...]
     * Available since 5.0.0.
     * Time complexity: For each stream mentioned: O(N) with N being the number of elements being returned,
     * it menas that XREAD-ing with a fixed COUNT is O(1). Note that when the BLOCK option is used,
     * XADD will pay O(M) time in order to serve the M clients blocked on the stream getting new data.
     * @link https://redis.io/commands/xread
     *
     * @param string|null $count
     * @param string|null $block
     * @param string|string[] $keys
     * @param string|string[] $ids
     * @return array
     */
    public function xread($keys, $ids, $count = null, $block = null) {
        $keys = (array)$keys;
        $params = [];
        if (isset($count)) {
            $params[] = ['COUNT', $count];
        }
        if (isset($block)) {
            $params[] = ['BLOCK', $block];
        }
        $params[] = 'STREAMS';
        $params[] = $keys;
        $params[] = (array)$ids;
        return $this->returnCommand(['XREAD'], $keys, $params);
    }

    /**
     * XREADGROUP GROUP group consumer [COUNT count] [BLOCK milliseconds] [NOACK] STREAMS key [key ...] ID [ID ...]
     * Available since 5.0.0.
     * Time complexity: For each stream mentioned: O(M) with M being the number of elements returned.
     * If M is constant (e.g. always asking for the first 10 elements with COUNT), you can consider it O(1).
     * On the other side when XREADGROUP blocks, XADD will pay the O(N) time in order to serve the N clients blocked on
     * the stream getting new data.
     * @link https://redis.io/commands/xreadgroup
     *
     * @param string $group
     * @param string $consumer
     * @param string|null $count
     * @param string|null $block
     * @param bool $noack
     * @param string|string[] $keys
     * @param string|string[] $ids
     * @return array
     */
    public function xreadgroup($group, $consumer, $keys, $ids, $noack = false, $count = null, $block = null) {
        $keys = (array)$keys;
        $params = ['GROUP', $group, $consumer];
        if (isset($count)) {
            $params[] = ['COUNT', $count];
        }
        if (isset($block)) {
            $params[] = ['BLOCK', $block];
        }
        if ($noack) {
            $params[] = 'NOACK';
        }
        $params[] = $keys;
        $params[] = (array)$ids;
        return $this->returnCommand(['XREADGROUP'], $keys, $params);
    }

    /**
     * XREVRANGE key end start [COUNT count]
     * Available since 5.0.0.
     * Time complexity: O(N) with N being the number of elements returned.
     * If N is constant (e.g. always asking for the first 10 elements with COUNT), you can consider it O(1).
     * @link https://redis.io/commands/xrevrange
     *
     * @param string $key
     * @param string $end
     * @param string $start
     * @param string|null $count
     * @return array
     */
    public function xrevrange($key, $end, $start, $count = null) {
        $params = [$key, $end, $start];
        if (isset($count)) {
            $params[] = ['COUNT', $count];
        }
        return $this->returnCommand(['XREVRANGE'], [$key], $params);
    }

    /**
     * XTRIM key MAXLEN [~] count
     * Available since 5.0.0.
     * Time complexity: O(N), with N being the number of evicted entries.
     * Constant times are very small however, since entries are organized in macro nodes containing multiple entries
     * that can be released with a single deallocation.
     * @link https://redis.io/commands/xtrim
     *
     * @param string $key
     * @param bool $withTilde
     * @param int $count
     * @return int
     */
    public function xtrim($key, $count, $withTilde = false) {
        $params = [$key, 'MAXLEN'];
        if ($withTilde) {
            $params[] = '~';
        }
        $params[] = $count;
        return $this->returnCommand(['XTRIM'], [$key], $params);
    }

    /**
     * XSETID <stream> <groupname> <id>
     * @link https://github.com/antirez/redis/blob/5c5197fe4f40ad697d5350529989f38122327bd1/src/t_stream.c
     *
     * @param string $stream
     * @param string $groupname
     * @param string $id
     * @return
     */
    public function xsetid($stream, $groupname, $id) {
        return $this->returnCommand(['XSETID'], null, [$stream, $groupname, $id]);
    }

}
