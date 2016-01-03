<?php

namespace RedisClient\Pipeline;

use RedisClient\Command\Response\ResponseParser;
use RedisClient\Command\Traits\AllCommandsTrait;

/**
 * Cluster
 * @method $this clusterAddslots($slots)
 * @method $this clusterCountFailureReports($nodeId)
 * @method $this clusterCountkeysinslot($slot)
 * @method $this clusterDelslots($slots)
 * @method $this clusterFailover($option = null)
 * @method $this clusterForget($nodeId)
 * @method $this clusterGetkeysinslot($slot, $count)
 * @method $this clusterInfo($slot, $count)
 * @method $this clusterKeyslot($key)
 * @method $this clusterMeet($ip, $port)
 * @method $this clusterNodes()
 * @method $this clusterReplicate($nodeId)
 * @method $this clusterReset($option = null)
 * @method $this clusterSaveconfig()
 * @method $this clusterSetConfigEpoch($config)
 * @method $this clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method $this clusterSlaves($nodeId)
 * @method $this clusterSlots()
 * @method $this readonly()
 * @method $this readwrite()
 * Connection
 * @method $this auth($password)
 * @method $this echoMessage($message)
 * @method $this ping($message = null)
 * @method $this quit()
 * @method $this select($db)
 * Hashes
 * @method $this hdel($key, $fields)
 * @method $this hexists($key, $field)
 * @method $this hget($key, $field)
 * @method $this hgetall($key)
 * @method $this hincrby($key, $field, $increment)
 * @method $this hincrbyfloat($key, $field, $increment)
 * @method $this hkeys($key)
 * @method $this hlen($key)
 * @method $this hmget($key, $fields)
 * @method $this hmset($key, array $fieldValues)
 * @method $this hset($key, $field, $value)
 * @method $this hsetnx($key, $field, $value)
 * @method $this hstrlen($key, $field)
 * @method $this hvals($key)
 * @method $this hscan($key, $cursor, $pattern = null, $count = null)
 * HyperLogLog
 * @method $this pfadd($key, $elements)
 * @method $this pfcount($keys)
 * @method $this pfmerge($destkey, $sourcekeys)
 * Keys
 * @method $this del($keys)
 * @method $this dump($key)
 * @method $this exists($keys)
 * @method $this expire($key, $seconds)
 * @method $this expireAt($key, $timestamp)
 * @method $this keys($pattern)
 * @method $this migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method $this move($key, $db)
 * @method $this object($subcommand, $arguments = null)
 * @method $this persist($key)
 * @method $this pexpire($key, $milliseconds)
 * @method $this pexpireat($key, $millisecondsTimestamp)
 * @method $this pttl($key)
 * @method $this randomkey()
 * @method $this rename($key, $newkey)
 * @method $this renamenx($key, $newkey)
 * @method $this restore($key, $ttl, $serializedValue, $replace = false)
 * @method $this scan($cursor, $pattern = null, $count = null)
 * @method $this sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method $this ttl($key)
 * @method $this type($key)
 * @method $this wait($numslaves, $timeout)
 * Lists
 * @method $this blpop($keys, $timeout)
 * @method $this brpop($keys, $timeout)
 * @method $this brpoplpush($source, $destination, $timeout)
 * @method $this lindex($key, $index)
 * @method $this linsert($key, $after = true, $pivot, $value)
 * @method $this llen($key)
 * @method $this lpop($key)
 * @method $this lpush($key, $values)
 * @method $this lpushx($key, $value)
 * @method $this lrange($key, $start, $stop)
 * @method $this lrem($key, $count, $value)
 * @method $this lset($key, $index, $value)
 * @method $this ltrim($key, $start, $stop)
 * @method $this rpop($key)
 * @method $this rpoplpush($source, $destination)
 * @method $this rpush($key, $values)
 * @method $this rpushx($key, $value)
 * PubSub
 * @method $this psubscribe($patterns)
 * @method $this publish($channel, $message)
 * @method $this pubsub($subcommand, $arguments = null)
 * @method $this punsubscribe($patterns = null)
 * @method $this subscribe($channels)
 * @method $this unsubscribe($channels)
 * Scripting
 * @method $this evalScript($script, $keys = null, $args = null)
 * @method $this evalsha($sha, $keys = null, $args = null)
 * @method $this scriptExists($scriptsSha)
 * @method $this scriptFlush()
 * @method $this scriptKill()
 * @method $this scriptLoad($script)
 * Server
 * @method $this bgrewriteaof()
 * @method $this bgsave()
 * @method $this clientGetname()
 * @method $this clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method $this clientList()
 * @method $this clientPause($timeout)
 * @method $this clientSetname($connectionName)
 * @method $this command()
 * @method $this commandCount()
 * @method $this commandGetkeys($command)
 * @method $this commandInfo($commandNames)
 * @method $this configGet($parameter)
 * @method $this configResetstat()
 * @method $this configRewrite()
 * @method $this configSet($parameter, $value)
 * @method $this dbsize()
 * @method $this debugObject($key)
 * @method $this debugSegfault()
 * @method $this flushall()
 * @method $this flushdb()
 * @method $this info($section = null)
 * @method $this lastsave()
 * @method $this monitor()
 * @method $this role()
 * @method $this save()
 * @method $this shutdown($save)
 * @method $this slaveof($host, $port)
 * @method $this slowlog($subcommand, $argument = null)
 * @method $this sync()
 * @method $this time()
 * Sets
 * @method $this sadd($key, $members)
 * @method $this scard($key)
 * @method $this sdiff($keys)
 * @method $this sdiffstore($destination, $keys)
 * @method $this sinter($keys)
 * @method $this sinterstore($destination, $keys)
 * @method $this sismember($key, $member)
 * @method $this smembers($key)
 * @method $this smove($source, $destination, $member)
 * @method $this spop($key, $count = null)
 * @method $this srandmember($key, $count = null)
 * @method $this srem($key, $members)
 * @method $this sscan($key, $cursor, $pattern = null, $count = null)
 * @method $this sunion($keys)
 * @method $this sunionstore($destination, $keys)
 * SortedSet
 * @method $this zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 * @method $this zcard($key)
 * @method $this zcount($key, $min, $max)
 * @method $this zincrby($key, $increment, $member)
 * @method $this zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method $this zlexcount($key, $min, $max)
 * @method $this zrange($key, $start, $stop, $withscores = false)
 * @method $this zrangebylex($key, $min, $max, $limit = null)
 * @method $this zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method $this zrank($key, $member)
 * @method $this zrem($key, $members)
 * @method $this zremrangebylex($key, $min, $max)
 * @method $this zremrangebyrank($key, $start, $stop)
 * @method $this zremrangebyscore($key, $min, $max)
 * @method $this zrevrange($key, $start, $stop, $withscores = false)
 * @method $this zrevrangebylex($key, $max, $min, $limit = null)
 * @method $this zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method $this zrevrank($key, $member)
 * @method $this zscan($key, $cursor, $pattern = null, $count = null)
 * @method $this zscore($key, $member)
 * @method $this zunionstore($destination, $keys, $weights = null, $aggregate = null)
 * Strings
 * @method $this append($key, $value)
 * @method $this bitcount($key, $start = null, $end = null)
 * @method $this bitop($operation, $destkey, $keys)
 * @method $this bitpos($key, $bit, $start = null, $end = null)
 * @method $this decr($key)
 * @method $this decrby($key, $decrement)
 * @method $this get($key)
 * @method $this getbit($key, $offset)
 * @method $this getrange($key, $start, $end)
 * @method $this getset($key, $value)
 * @method $this incr($key)
 * @method $this incrby($key, $increment)
 * @method $this incrbyfloat($key, $increment)
 * @method $this mget($keys)
 * @method $this mset(array $keyValues)
 * @method $this msetnx(array $keyValues)
 * @method $this psetex($key, $milliseconds, $value)
 * @method $this set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method $this setbit($key, $offset, $bit)
 * @method $this setex($key, $seconds, $value)
 * @method $this setnx($key, $value)
 * @method $this setrange($key, $offset, $value)
 * @method $this strlen($key)
 * Transactions
 * @method $this discard()
 * @method $this exec()
 * @method $this multi()
 * @method $this unwatch()
 * @method $this watch($keys)
 */
class Pipeline {
    use AllCommandsTrait;

    /**
     * @var array[]
     */
    protected $commandLines = [];

    public function __construct(\Closure $Closure = null) {
        if ($Closure) {
            $Closure($this);
        }
    }

    /**
     * @inheritdoc
     * @return $this
     */
    protected function returnCommand(array $command, array $params = null, $parserId = null) {
        $this->commandLines[] = [$command, $params, $parserId];
        return $this;
    }

    /**
     * @return array[]
     */
    public function getStructure() {
        $structures = [];
        foreach ($this->commandLines as $commandLine) {
            $structure = $commandLine[0];
            if (isset($commandLine[1])) {
                foreach ($commandLine[1] as $params) {
                    if (is_array($params)) {
                        foreach ($params as $param) {
                            $structure[] = $param;
                        }
                    } else {
                        $structure[] = $params;
                    }
                }
            }
            $structures[] = $structure;
        }
        return $structures;
    }

    /**
     * @param array $responses
     * @return mixed
     */
    public function parseResponse($responses) {
        foreach ($responses as $n => $response) {
            if (empty($this->commandLines[$n][2])) {
                // todo: check
                continue;
            }
            $responses[$n] = ResponseParser::parse($this->commandLines[$n][2], $response);
        }
        return $responses;
    }

}
