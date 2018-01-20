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
namespace RedisClient\Pipeline\Version;

use RedisClient\Command\Traits\Version3x0\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline3x0 auth($password)
 * @method Pipeline3x0 echo($message)
 * @method Pipeline3x0 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline3x0 ping()
 * @method Pipeline3x0 quit()
 * @method Pipeline3x0 select($db)
 *
 * Hashes
 * @method Pipeline3x0 hdel($key, $fields)
 * @method Pipeline3x0 hexists($key, $field)
 * @method Pipeline3x0 hget($key, $field)
 * @method Pipeline3x0 hgetall($key)
 * @method Pipeline3x0 hincrby($key, $field, $increment)
 * @method Pipeline3x0 hincrbyfloat($key, $field, $increment)
 * @method Pipeline3x0 hkeys($key)
 * @method Pipeline3x0 hlen($key)
 * @method Pipeline3x0 hmget($key, $fields)
 * @method Pipeline3x0 hmset($key, array $fieldValues)
 * @method Pipeline3x0 hset($key, $field, $value)
 * @method Pipeline3x0 hsetnx($key, $field, $value)
 * @method Pipeline3x0 hvals($key)
 *
 * Keys
 * @method Pipeline3x0 del($keys)
 * @method Pipeline3x0 dump($key)
 * -method Pipeline3x0 exists($key)
 * @method Pipeline3x0 expire($key, $seconds)
 * @method Pipeline3x0 expireAt($key, $timestamp)
 * @method Pipeline3x0 keys($pattern)
 * -method Pipeline3x0 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline3x0 move($key, $db)
 * @method Pipeline3x0 object($subcommand, $arguments = null)
 * @method Pipeline3x0 persist($key)
 * @method Pipeline3x0 pexpire($key, $milliseconds)
 * @method Pipeline3x0 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline3x0 pttl($key)
 * @method Pipeline3x0 randomkey()
 * @method Pipeline3x0 rename($key, $newkey)
 * @method Pipeline3x0 renamenx($key, $newkey)
 * -method Pipeline3x0 restore($key, $ttl, $serializedValue)
 * @method Pipeline3x0 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline3x0 ttl($key)
 * @method Pipeline3x0 type($key)
 *
 * Lists
 * @method Pipeline3x0 blpop($keys, $timeout)
 * @method Pipeline3x0 brpop($keys, $timeout)
 * @method Pipeline3x0 brpoplpush($source, $destination, $timeout)
 * @method Pipeline3x0 lindex($key, $index)
 * @method Pipeline3x0 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline3x0 llen($key)
 * @method Pipeline3x0 lpop($key)
 * @method Pipeline3x0 lpush($key, $values)
 * @method Pipeline3x0 lpushx($key, $value)
 * @method Pipeline3x0 lrange($key, $start, $stop)
 * @method Pipeline3x0 lrem($key, $count, $value)
 * @method Pipeline3x0 lset($key, $index, $value)
 * @method Pipeline3x0 ltrim($key, $start, $stop)
 * @method Pipeline3x0 rpop($key)
 * @method Pipeline3x0 rpoplpush($source, $destination)
 * @method Pipeline3x0 rpush($key, $values)
 * @method Pipeline3x0 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline3x0 publish($channel, $message)
 * @method Pipeline3x0 punsubscribe($patterns = null)
 * @method Pipeline3x0 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline3x0 eval($script, $keys = null, $args = null)
 * @method Pipeline3x0 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline3x0 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline3x0 scriptExists($sha1)
 * @method Pipeline3x0 scriptFlush()
 * @method Pipeline3x0 scriptKill()
 * @method Pipeline3x0 scriptLoad($script)
 *
 * Server
 * @method Pipeline3x0 bgrewriteaof()
 * @method Pipeline3x0 bgsave()
 * @method Pipeline3x0 clientGetname()
 * @method Pipeline3x0 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline3x0 clientList()
 * @method Pipeline3x0 clientSetname($connectionName)
 * @method Pipeline3x0 configGet($parameter)
 * @method Pipeline3x0 configResetstat()
 * @method Pipeline3x0 configSet($parameter, $value)
 * @method Pipeline3x0 dbsize()
 * @method Pipeline3x0 debugObject($key)
 * @method Pipeline3x0 debugSegfault()
 * @method Pipeline3x0 flushall()
 * @method Pipeline3x0 flushdb()
 * @method Pipeline3x0 info($section = null)
 * @method Pipeline3x0 lastsave()
 * @method Pipeline3x0 save()
 * @method Pipeline3x0 shutdown($save)
 * @method Pipeline3x0 slaveof($host, $port)
 * @method Pipeline3x0 slowlog($subcommand, $argument = null)
 * @method Pipeline3x0 sync()
 * @method Pipeline3x0 time()
 *
 * Sets
 * @method Pipeline3x0 sadd($key, $members)
 * @method Pipeline3x0 scard($key)
 * @method Pipeline3x0 sdiff($keys)
 * @method Pipeline3x0 sdiffstore($destination, $keys)
 * @method Pipeline3x0 sinter($keys)
 * @method Pipeline3x0 sinterstore($destination, $keys)
 * @method Pipeline3x0 sismember($key, $member)
 * @method Pipeline3x0 smembers($key)
 * @method Pipeline3x0 smove($source, $destination, $member)
 * @method Pipeline3x0 spop($key)
 * @method Pipeline3x0 srandmember($key, $count = null)
 * @method Pipeline3x0 srem($key, $members)
 * @method Pipeline3x0 sunion($keys)
 * @method Pipeline3x0 sunionstore($destination, $keys)
 *
 * SortedSets
 * -method Pipeline3x0 zadd($key, array $members)
 * @method Pipeline3x0 zcard($key)
 * @method Pipeline3x0 zcount($key, $min, $max)
 * @method Pipeline3x0 zincrby($key, $increment, $member)
 * @method Pipeline3x0 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline3x0 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline3x0 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline3x0 zrank($key, $member)
 * @method Pipeline3x0 zrem($key, $members)
 * @method Pipeline3x0 zremrangebyrank($key, $start, $stop)
 * @method Pipeline3x0 zremrangebyscore($key, $min, $max)
 * @method Pipeline3x0 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline3x0 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline3x0 zrevrank($key, $member)
 * @method Pipeline3x0 zscore($key, $member)
 * @method Pipeline3x0 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline3x0 append($key, $value)
 * @method Pipeline3x0 bitcount($key, $start = null, $end = null)
 * @method Pipeline3x0 bitop($operation, $destkey, $keys)
 * @method Pipeline3x0 decr($key)
 * @method Pipeline3x0 decrby($key, $decrement)
 * @method Pipeline3x0 get($key)
 * @method Pipeline3x0 getbit($key, $offset)
 * @method Pipeline3x0 getrange($key, $start, $end)
 * @method Pipeline3x0 substr($key, $start, $end)
 * @method Pipeline3x0 getset($key, $value)
 * @method Pipeline3x0 incr($key)
 * @method Pipeline3x0 incrby($key, $increment)
 * @method Pipeline3x0 incrbyfloat($key, $increment)
 * @method Pipeline3x0 mget($keys)
 * @method Pipeline3x0 mset(array $keyValues)
 * @method Pipeline3x0 msetnx(array $keyValues)
 * @method Pipeline3x0 psetex($key, $milliseconds, $value)
 * @method Pipeline3x0 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline3x0 setbit($key, $offset, $bit)
 * @method Pipeline3x0 setex($key, $seconds, $value)
 * @method Pipeline3x0 setnx($key, $value)
 * @method Pipeline3x0 setrange($key, $offset, $value)
 * @method Pipeline3x0 strlen($key)
 *
 * Transactions
 * @method Pipeline3x0 discard()
 * @method Pipeline3x0 exec()
 * @method Pipeline3x0 multi()
 * @method Pipeline3x0 unwatch()
 * @method Pipeline3x0 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline3x0 ping($message = null)
 *
 * Hashes
 * @method Pipeline3x0 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline3x0 pfadd($key, $elements)
 * @method Pipeline3x0 pfcount($keys)
 * @method Pipeline3x0 pfmerge($destkey, $sourcekeys)
 * @method Pipeline3x0 pfdebug($subcommand, $key)
 * @method Pipeline3x0 pfselftest()
 *
 * Keys
 * @method Pipeline3x0 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline3x0 latencyLatest()
 * @method Pipeline3x0 latencyHistory($eventName)
 * @method Pipeline3x0 latencyReset($eventNames = null)
 * @method Pipeline3x0 latencyGraph($eventName)
 * @method Pipeline3x0 latencyDoctor()
 *
 * PubSub
 * @method Pipeline3x0 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline3x0 command()
 * @method Pipeline3x0 commandCount()
 * @method Pipeline3x0 commandGetkeys($command)
 * @method Pipeline3x0 commandInfo($commandNames)
 * @method Pipeline3x0 configRewrite()
 * @method Pipeline3x0 role()
 *
 * Sets
 * @method Pipeline3x0 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline3x0 zlexcount($key, $min, $max)
 * @method Pipeline3x0 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline3x0 zremrangebylex($key, $min, $max)
 * @method Pipeline3x0 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline3x0 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline3x0 bitpos($key, $bit, $start = null, $end = null)
 *
 * Redis version 2.9
 *
 * Server
 * @method Pipeline3x0 clientPause($timeout)
 *
 * Redis version 3.0
 *
 * Cluster
 * @method Pipeline3x0 clusterAddslots($slots)
 * @method Pipeline3x0 clusterCountFailureReports($nodeId)
 * @method Pipeline3x0 clusterCountkeysinslot($slot)
 * @method Pipeline3x0 clusterDelslots($slots)
 * @method Pipeline3x0 clusterFailover($option = null)
 * @method Pipeline3x0 clusterForget($nodeId)
 * @method Pipeline3x0 clusterGetkeysinslot($slot, $count)
 * @method Pipeline3x0 clusterInfo()
 * @method Pipeline3x0 clusterKeyslot($key)
 * @method Pipeline3x0 clusterMeet($ip, $port)
 * @method Pipeline3x0 clusterNodes()
 * @method Pipeline3x0 clusterReplicate($nodeId)
 * @method Pipeline3x0 clusterReset($option = null)
 * @method Pipeline3x0 clusterSaveconfig()
 * @method Pipeline3x0 clusterSetConfigEpoch($config)
 * @method Pipeline3x0 clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method Pipeline3x0 clusterSlaves($nodeId)
 * @method Pipeline3x0 clusterSlots()
 * @method Pipeline3x0 readonly()
 * @method Pipeline3x0 readwrite()
 *
 * Keys
 * @method Pipeline3x0 exists($keys)
 * @method Pipeline3x0 migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline3x0 restore($key, $ttl, $serializedValue, $replace = false)
 * @method Pipeline3x0 wait($numslaves, $timeout)
 *
 * SortedSets
 * @method Pipeline3x0 zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 * 
 */
class Pipeline3x0 extends AbstractPipeline {
    use CommandsTrait;
}
