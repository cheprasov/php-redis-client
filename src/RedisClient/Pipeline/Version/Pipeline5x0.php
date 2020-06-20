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

use RedisClient\Command\Traits\Version5x0\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline5x0 auth($password)
 * @method Pipeline5x0 echo($message)
 * @method Pipeline5x0 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline5x0 ping()
 * @method Pipeline5x0 quit()
 * @method Pipeline5x0 select($db)
 *
 * Hashes
 * @method Pipeline5x0 hdel($key, $fields)
 * @method Pipeline5x0 hexists($key, $field)
 * @method Pipeline5x0 hget($key, $field)
 * @method Pipeline5x0 hgetall($key)
 * @method Pipeline5x0 hincrby($key, $field, $increment)
 * @method Pipeline5x0 hincrbyfloat($key, $field, $increment)
 * @method Pipeline5x0 hkeys($key)
 * @method Pipeline5x0 hlen($key)
 * @method Pipeline5x0 hmget($key, $fields)
 * @method Pipeline5x0 hmset($key, array $fieldValues)
 * @method Pipeline5x0 hset($key, $field, $value)
 * @method Pipeline5x0 hsetnx($key, $field, $value)
 * @method Pipeline5x0 hvals($key)
 *
 * Keys
 * @method Pipeline5x0 del($keys)
 * @method Pipeline5x0 dump($key)
 * -method Pipeline5x0 exists($key)
 * @method Pipeline5x0 expire($key, $seconds)
 * @method Pipeline5x0 expireAt($key, $timestamp)
 * @method Pipeline5x0 keys($pattern)
 * -method Pipeline5x0 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline5x0 move($key, $db)
 * @method Pipeline5x0 object($subcommand, $arguments = null)
 * @method Pipeline5x0 persist($key)
 * @method Pipeline5x0 pexpire($key, $milliseconds)
 * @method Pipeline5x0 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline5x0 pttl($key)
 * @method Pipeline5x0 randomkey()
 * @method Pipeline5x0 rename($key, $newkey)
 * @method Pipeline5x0 renamenx($key, $newkey)
 * -method Pipeline5x0 restore($key, $ttl, $serializedValue)
 * @method Pipeline5x0 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline5x0 ttl($key)
 * @method Pipeline5x0 type($key)
 *
 * Lists
 * @method Pipeline5x0 blpop($keys, $timeout)
 * @method Pipeline5x0 brpop($keys, $timeout)
 * @method Pipeline5x0 brpoplpush($source, $destination, $timeout)
 * @method Pipeline5x0 lindex($key, $index)
 * @method Pipeline5x0 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline5x0 llen($key)
 * @method Pipeline5x0 lpop($key)
 * @method Pipeline5x0 lpush($key, $values)
 * @method Pipeline5x0 lpushx($key, $value)
 * @method Pipeline5x0 lrange($key, $start, $stop)
 * @method Pipeline5x0 lrem($key, $count, $value)
 * @method Pipeline5x0 lset($key, $index, $value)
 * @method Pipeline5x0 ltrim($key, $start, $stop)
 * @method Pipeline5x0 rpop($key)
 * @method Pipeline5x0 rpoplpush($source, $destination)
 * @method Pipeline5x0 rpush($key, $values)
 * @method Pipeline5x0 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline5x0 publish($channel, $message)
 * @method Pipeline5x0 punsubscribe($patterns = null)
 * @method Pipeline5x0 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline5x0 eval($script, $keys = null, $args = null)
 * @method Pipeline5x0 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline5x0 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline5x0 scriptExists($sha1)
 * @method Pipeline5x0 scriptFlush()
 * @method Pipeline5x0 scriptKill()
 * @method Pipeline5x0 scriptLoad($script)
 *
 * Server
 * @method Pipeline5x0 bgrewriteaof()
 * @method Pipeline5x0 bgsave()
 * @method Pipeline5x0 clientGetname()
 * @method Pipeline5x0 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline5x0 clientList()
 * @method Pipeline5x0 clientSetname($connectionName)
 * @method Pipeline5x0 configGet($parameter)
 * @method Pipeline5x0 configResetstat()
 * @method Pipeline5x0 configSet($parameter, $value)
 * @method Pipeline5x0 dbsize()
 * @method Pipeline5x0 debugObject($key)
 * @method Pipeline5x0 debugSegfault()
 * -method Pipeline5x0 flushall()
 * -method Pipeline5x0 flushdb()
 * @method Pipeline5x0 info($section = null)
 * @method Pipeline5x0 lastsave()
 * @method Pipeline5x0 save()
 * @method Pipeline5x0 shutdown($save)
 * @method Pipeline5x0 slaveof($host, $port)
 * @method Pipeline5x0 slowlog($subcommand, $argument = null)
 * @method Pipeline5x0 sync()
 * @method Pipeline5x0 time()
 *
 * Sets
 * @method Pipeline5x0 sadd($key, $members)
 * @method Pipeline5x0 scard($key)
 * @method Pipeline5x0 sdiff($keys)
 * @method Pipeline5x0 sdiffstore($destination, $keys)
 * @method Pipeline5x0 sinter($keys)
 * @method Pipeline5x0 sinterstore($destination, $keys)
 * @method Pipeline5x0 sismember($key, $member)
 * @method Pipeline5x0 smembers($key)
 * @method Pipeline5x0 smove($source, $destination, $member)
 * -method Pipeline5x0 spop($key)
 * @method Pipeline5x0 srandmember($key, $count = null)
 * @method Pipeline5x0 srem($key, $members)
 * @method Pipeline5x0 sunion($keys)
 * @method Pipeline5x0 sunionstore($destination, $keys)
 *
 * SortedSets
 * -method Pipeline5x0 zadd($key, array $members)
 * @method Pipeline5x0 zcard($key)
 * @method Pipeline5x0 zcount($key, $min, $max)
 * @method Pipeline5x0 zincrby($key, $increment, $member)
 * @method Pipeline5x0 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline5x0 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline5x0 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline5x0 zrank($key, $member)
 * @method Pipeline5x0 zrem($key, $members)
 * @method Pipeline5x0 zremrangebyrank($key, $start, $stop)
 * @method Pipeline5x0 zremrangebyscore($key, $min, $max)
 * @method Pipeline5x0 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline5x0 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline5x0 zrevrank($key, $member)
 * @method Pipeline5x0 zscore($key, $member)
 * @method Pipeline5x0 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline5x0 append($key, $value)
 * @method Pipeline5x0 bitcount($key, $start = null, $end = null)
 * @method Pipeline5x0 bitop($operation, $destkey, $keys)
 * @method Pipeline5x0 decr($key)
 * @method Pipeline5x0 decrby($key, $decrement)
 * @method Pipeline5x0 get($key)
 * @method Pipeline5x0 getbit($key, $offset)
 * @method Pipeline5x0 getrange($key, $start, $end)
 * @method Pipeline5x0 substr($key, $start, $end)
 * @method Pipeline5x0 getset($key, $value)
 * @method Pipeline5x0 incr($key)
 * @method Pipeline5x0 incrby($key, $increment)
 * @method Pipeline5x0 incrbyfloat($key, $increment)
 * @method Pipeline5x0 mget($keys)
 * @method Pipeline5x0 mset(array $keyValues)
 * @method Pipeline5x0 msetnx(array $keyValues)
 * @method Pipeline5x0 psetex($key, $milliseconds, $value)
 * @method Pipeline5x0 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline5x0 setbit($key, $offset, $bit)
 * @method Pipeline5x0 setex($key, $seconds, $value)
 * @method Pipeline5x0 setnx($key, $value)
 * @method Pipeline5x0 setrange($key, $offset, $value)
 * @method Pipeline5x0 strlen($key)
 *
 * Transactions
 * @method Pipeline5x0 discard()
 * @method Pipeline5x0 exec()
 * @method Pipeline5x0 multi()
 * @method Pipeline5x0 unwatch()
 * @method Pipeline5x0 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline5x0 ping($message = null)
 *
 * Hashes
 * @method Pipeline5x0 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline5x0 pfadd($key, $elements)
 * @method Pipeline5x0 pfcount($keys)
 * @method Pipeline5x0 pfmerge($destkey, $sourcekeys)
 * @method Pipeline5x0 pfdebug($subcommand, $key)
 * @method Pipeline5x0 pfselftest()
 *
 * Keys
 * @method Pipeline5x0 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline5x0 latencyLatest()
 * @method Pipeline5x0 latencyHistory($eventName)
 * @method Pipeline5x0 latencyReset($eventNames = null)
 * @method Pipeline5x0 latencyGraph($eventName)
 * @method Pipeline5x0 latencyDoctor()
 *
 * PubSub
 * @method Pipeline5x0 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline5x0 command()
 * @method Pipeline5x0 commandCount()
 * @method Pipeline5x0 commandGetkeys($command)
 * @method Pipeline5x0 commandInfo($commandNames)
 * @method Pipeline5x0 configRewrite()
 * @method Pipeline5x0 role()
 *
 * Sets
 * @method Pipeline5x0 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline5x0 zlexcount($key, $min, $max)
 * @method Pipeline5x0 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline5x0 zremrangebylex($key, $min, $max)
 * @method Pipeline5x0 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline5x0 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline5x0 bitpos($key, $bit, $start = null, $end = null)
 *
 * Redis version 2.9
 *
 * Server
 * @method Pipeline5x0 clientPause($timeout)
 *
 * Redis version 3.0
 *
 * Cluster
 * @method Pipeline5x0 clusterAddslots($slots)
 * @method Pipeline5x0 clusterCountFailureReports($nodeId)
 * @method Pipeline5x0 clusterCountkeysinslot($slot)
 * @method Pipeline5x0 clusterDelslots($slots)
 * @method Pipeline5x0 clusterFailover($option = null)
 * @method Pipeline5x0 clusterForget($nodeId)
 * @method Pipeline5x0 clusterGetkeysinslot($slot, $count)
 * @method Pipeline5x0 clusterInfo()
 * @method Pipeline5x0 clusterKeyslot($key)
 * @method Pipeline5x0 clusterMeet($ip, $port)
 * @method Pipeline5x0 clusterNodes()
 * @method Pipeline5x0 clusterReplicate($nodeId)
 * @method Pipeline5x0 clusterReset($option = null)
 * @method Pipeline5x0 clusterSaveconfig()
 * @method Pipeline5x0 clusterSetConfigEpoch($config)
 * @method Pipeline5x0 clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method Pipeline5x0 clusterSlaves($nodeId)
 * @method Pipeline5x0 clusterSlots()
 * @method Pipeline5x0 readonly()
 * @method Pipeline5x0 readwrite()
 *
 * Keys
 * @method Pipeline5x0 exists($keys)
 * -method Pipeline5x0 migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline5x0 restore($key, $ttl, $serializedValue, $replace = false)
 * @method Pipeline5x0 wait($numslaves, $timeout)
 *
 * SortedSets
 * @method Pipeline5x0 zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 *
 * Redis version 3.2
 *
 * Geo
 * @method Pipeline5x0 geoadd($key, array $members)
 * @method Pipeline5x0 geodist($key, $member1, $member2, $unit = null)
 * @method Pipeline5x0 geohash($key, $members)
 * @method Pipeline5x0 geopos($key, $members)
 * @method Pipeline5x0 georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline5x0 georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline5x0 geodel($key, $members)
 *
 * Hashes
 * @method Pipeline5x0 hstrlen($key, $field)
 *
 * Keys
 * @method Pipeline5x0 migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline5x0 touch($keys)
 *
 * Scripting
 * @method Pipeline5x0 scriptDebug($param)
 *
 * Server
 * @method Pipeline5x0 clientReply($param)
 * @method Pipeline5x0 debugHelp()
 *
 * Sets
 * @method Pipeline5x0 spop($key, $count = null)
 *
 * Strings
 * @method Pipeline5x0 bitfield($key, array $subcommands)
 *
 * Redis version 4.0
 *
 * Connection
 * @method Pipeline5x0 swapdb($db1, $db2)
 *
 * Keys
 * @method Pipeline5x0 unlink($keys)
 *
 * Memory
 * @method Pipeline5x0 memoryDoctor()
 * @method Pipeline5x0 memoryHelp()
 * @method Pipeline5x0 memoryUsage($key, $count = null)
 * @method Pipeline5x0 memoryStats()
 * @method Pipeline5x0 memoryPurge()
 * @method Pipeline5x0 memoryMallocStats()
 *
 * Server
 * @method Pipeline5x0 flushall($async = false)
 * @method Pipeline5x0 flushdb($async = false)
 *
 * Redis version 5.0
 *
 * Connection
 * @method Pipeline5x0 clientId()
 * @method Pipeline5x0 clientUnblock($clientId, $timeoutOrError = null)
 *
 * Server
 * @method Pipeline5x0 lolwut($param1 = null, $param2 = null, $param3 = null)
 * @method Pipeline5x0 replicaof($host, $port)
 *
 * SortedSets
 * @method Pipeline5x0 bzpopmax($keys, $timeout = 0)
 * @method Pipeline5x0 bzpopmin($keys, $timeout = 0)
 * @method Pipeline5x0 zpopmax($key, $count = null)
 * @method Pipeline5x0 zpopmin($key, $count = null)
 *
 * Streams
 * @method Pipeline5x0 xack($key, $group, $ids)
 * @method Pipeline5x0 xadd($key, $id, $fieldStrings, $maxlen = null)
 * @method Pipeline5x0 xclaim($key, $group, $consumer, $minIdleTime, $ids, $idle = null, $time = null, $retrycount = null, $force = false, $justid = false)
 * @method Pipeline5x0 xdel($key, $ids)
 * @method Pipeline5x0 xgroupCreate($key, $groupname, $id)
 * @method Pipeline5x0 xgroupSetid($key, $groupname, $id)
 * @method Pipeline5x0 xgroupDestroy($key, $groupname)
 * @method Pipeline5x0 xgroupDelconsumer($key, $groupname, $consumername)
 * @method Pipeline5x0 xgroupHelp()
 * @method Pipeline5x0 xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $help = false)
 * @method Pipeline5x0 xlen($key)
 * @method Pipeline5x0 xpending($key, $group, $start = null, $end = null, $count = null, $consumer = null)
 * @method Pipeline5x0 xrange($key, $start, $end, $count = null)
 * @method Pipeline5x0 xread($keys, $ids, $count = null, $block = null)
 * @method Pipeline5x0 xreadgroup($group, $consumer, $keys, $ids, $noack = false, $count = null, $block = null)
 * @method Pipeline5x0 xrevrange($key, $end, $start, $count = null)
 * @method Pipeline5x0 xtrim($key, $count, $withTilde = false)
 * @method Pipeline5x0 xsetid($stream, $groupname, $id)
 * 
 */
class Pipeline5x0 extends AbstractPipeline {
    use CommandsTrait;
}
