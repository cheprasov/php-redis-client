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

use RedisClient\Command\Traits\Version4x0\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline4x0 auth($password)
 * @method Pipeline4x0 echo($message)
 * @method Pipeline4x0 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline4x0 ping()
 * @method Pipeline4x0 quit()
 * @method Pipeline4x0 select($db)
 *
 * Hashes
 * @method Pipeline4x0 hdel($key, $fields)
 * @method Pipeline4x0 hexists($key, $field)
 * @method Pipeline4x0 hget($key, $field)
 * @method Pipeline4x0 hgetall($key)
 * @method Pipeline4x0 hincrby($key, $field, $increment)
 * @method Pipeline4x0 hincrbyfloat($key, $field, $increment)
 * @method Pipeline4x0 hkeys($key)
 * @method Pipeline4x0 hlen($key)
 * @method Pipeline4x0 hmget($key, $fields)
 * @method Pipeline4x0 hmset($key, array $fieldValues)
 * @method Pipeline4x0 hset($key, $field, $value)
 * @method Pipeline4x0 hsetnx($key, $field, $value)
 * @method Pipeline4x0 hvals($key)
 *
 * Keys
 * @method Pipeline4x0 del($keys)
 * @method Pipeline4x0 dump($key)
 * -method Pipeline4x0 exists($key)
 * @method Pipeline4x0 expire($key, $seconds)
 * @method Pipeline4x0 expireAt($key, $timestamp)
 * @method Pipeline4x0 keys($pattern)
 * -method Pipeline4x0 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline4x0 move($key, $db)
 * @method Pipeline4x0 object($subcommand, $arguments = null)
 * @method Pipeline4x0 persist($key)
 * @method Pipeline4x0 pexpire($key, $milliseconds)
 * @method Pipeline4x0 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline4x0 pttl($key)
 * @method Pipeline4x0 randomkey()
 * @method Pipeline4x0 rename($key, $newkey)
 * @method Pipeline4x0 renamenx($key, $newkey)
 * -method Pipeline4x0 restore($key, $ttl, $serializedValue)
 * @method Pipeline4x0 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline4x0 ttl($key)
 * @method Pipeline4x0 type($key)
 *
 * Lists
 * @method Pipeline4x0 blpop($keys, $timeout)
 * @method Pipeline4x0 brpop($keys, $timeout)
 * @method Pipeline4x0 brpoplpush($source, $destination, $timeout)
 * @method Pipeline4x0 lindex($key, $index)
 * @method Pipeline4x0 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline4x0 llen($key)
 * @method Pipeline4x0 lpop($key)
 * @method Pipeline4x0 lpush($key, $values)
 * @method Pipeline4x0 lpushx($key, $value)
 * @method Pipeline4x0 lrange($key, $start, $stop)
 * @method Pipeline4x0 lrem($key, $count, $value)
 * @method Pipeline4x0 lset($key, $index, $value)
 * @method Pipeline4x0 ltrim($key, $start, $stop)
 * @method Pipeline4x0 rpop($key)
 * @method Pipeline4x0 rpoplpush($source, $destination)
 * @method Pipeline4x0 rpush($key, $values)
 * @method Pipeline4x0 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline4x0 publish($channel, $message)
 * @method Pipeline4x0 punsubscribe($patterns = null)
 * @method Pipeline4x0 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline4x0 eval($script, $keys = null, $args = null)
 * @method Pipeline4x0 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline4x0 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline4x0 scriptExists($sha1)
 * @method Pipeline4x0 scriptFlush()
 * @method Pipeline4x0 scriptKill()
 * @method Pipeline4x0 scriptLoad($script)
 *
 * Server
 * @method Pipeline4x0 bgrewriteaof()
 * @method Pipeline4x0 bgsave()
 * @method Pipeline4x0 clientGetname()
 * @method Pipeline4x0 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline4x0 clientList()
 * @method Pipeline4x0 clientSetname($connectionName)
 * @method Pipeline4x0 configGet($parameter)
 * @method Pipeline4x0 configResetstat()
 * @method Pipeline4x0 configSet($parameter, $value)
 * @method Pipeline4x0 dbsize()
 * @method Pipeline4x0 debugObject($key)
 * @method Pipeline4x0 debugSegfault()
 * -method Pipeline4x0 flushall()
 * -method Pipeline4x0 flushdb()
 * @method Pipeline4x0 info($section = null)
 * @method Pipeline4x0 lastsave()
 * @method Pipeline4x0 save()
 * @method Pipeline4x0 shutdown($save)
 * @method Pipeline4x0 slaveof($host, $port)
 * @method Pipeline4x0 slowlog($subcommand, $argument = null)
 * @method Pipeline4x0 sync()
 * @method Pipeline4x0 time()
 *
 * Sets
 * @method Pipeline4x0 sadd($key, $members)
 * @method Pipeline4x0 scard($key)
 * @method Pipeline4x0 sdiff($keys)
 * @method Pipeline4x0 sdiffstore($destination, $keys)
 * @method Pipeline4x0 sinter($keys)
 * @method Pipeline4x0 sinterstore($destination, $keys)
 * @method Pipeline4x0 sismember($key, $member)
 * @method Pipeline4x0 smembers($key)
 * @method Pipeline4x0 smove($source, $destination, $member)
 * -method Pipeline4x0 spop($key)
 * @method Pipeline4x0 srandmember($key, $count = null)
 * @method Pipeline4x0 srem($key, $members)
 * @method Pipeline4x0 sunion($keys)
 * @method Pipeline4x0 sunionstore($destination, $keys)
 *
 * SortedSets
 * -method Pipeline4x0 zadd($key, array $members)
 * @method Pipeline4x0 zcard($key)
 * @method Pipeline4x0 zcount($key, $min, $max)
 * @method Pipeline4x0 zincrby($key, $increment, $member)
 * @method Pipeline4x0 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline4x0 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline4x0 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline4x0 zrank($key, $member)
 * @method Pipeline4x0 zrem($key, $members)
 * @method Pipeline4x0 zremrangebyrank($key, $start, $stop)
 * @method Pipeline4x0 zremrangebyscore($key, $min, $max)
 * @method Pipeline4x0 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline4x0 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline4x0 zrevrank($key, $member)
 * @method Pipeline4x0 zscore($key, $member)
 * @method Pipeline4x0 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline4x0 append($key, $value)
 * @method Pipeline4x0 bitcount($key, $start = null, $end = null)
 * @method Pipeline4x0 bitop($operation, $destkey, $keys)
 * @method Pipeline4x0 decr($key)
 * @method Pipeline4x0 decrby($key, $decrement)
 * @method Pipeline4x0 get($key)
 * @method Pipeline4x0 getbit($key, $offset)
 * @method Pipeline4x0 getrange($key, $start, $end)
 * @method Pipeline4x0 substr($key, $start, $end)
 * @method Pipeline4x0 getset($key, $value)
 * @method Pipeline4x0 incr($key)
 * @method Pipeline4x0 incrby($key, $increment)
 * @method Pipeline4x0 incrbyfloat($key, $increment)
 * @method Pipeline4x0 mget($keys)
 * @method Pipeline4x0 mset(array $keyValues)
 * @method Pipeline4x0 msetnx(array $keyValues)
 * @method Pipeline4x0 psetex($key, $milliseconds, $value)
 * @method Pipeline4x0 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline4x0 setbit($key, $offset, $bit)
 * @method Pipeline4x0 setex($key, $seconds, $value)
 * @method Pipeline4x0 setnx($key, $value)
 * @method Pipeline4x0 setrange($key, $offset, $value)
 * @method Pipeline4x0 strlen($key)
 *
 * Transactions
 * @method Pipeline4x0 discard()
 * @method Pipeline4x0 exec()
 * @method Pipeline4x0 multi()
 * @method Pipeline4x0 unwatch()
 * @method Pipeline4x0 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline4x0 ping($message = null)
 *
 * Hashes
 * @method Pipeline4x0 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline4x0 pfadd($key, $elements)
 * @method Pipeline4x0 pfcount($keys)
 * @method Pipeline4x0 pfmerge($destkey, $sourcekeys)
 * @method Pipeline4x0 pfdebug($subcommand, $key)
 * @method Pipeline4x0 pfselftest()
 *
 * Keys
 * @method Pipeline4x0 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline4x0 latencyLatest()
 * @method Pipeline4x0 latencyHistory($eventName)
 * @method Pipeline4x0 latencyReset($eventNames = null)
 * @method Pipeline4x0 latencyGraph($eventName)
 * @method Pipeline4x0 latencyDoctor()
 *
 * PubSub
 * @method Pipeline4x0 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline4x0 command()
 * @method Pipeline4x0 commandCount()
 * @method Pipeline4x0 commandGetkeys($command)
 * @method Pipeline4x0 commandInfo($commandNames)
 * @method Pipeline4x0 configRewrite()
 * @method Pipeline4x0 role()
 *
 * Sets
 * @method Pipeline4x0 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline4x0 zlexcount($key, $min, $max)
 * @method Pipeline4x0 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline4x0 zremrangebylex($key, $min, $max)
 * @method Pipeline4x0 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline4x0 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline4x0 bitpos($key, $bit, $start = null, $end = null)
 *
 * Redis version 2.9
 *
 * Server
 * @method Pipeline4x0 clientPause($timeout)
 *
 * Redis version 3.0
 *
 * Cluster
 * @method Pipeline4x0 clusterAddslots($slots)
 * @method Pipeline4x0 clusterCountFailureReports($nodeId)
 * @method Pipeline4x0 clusterCountkeysinslot($slot)
 * @method Pipeline4x0 clusterDelslots($slots)
 * @method Pipeline4x0 clusterFailover($option = null)
 * @method Pipeline4x0 clusterForget($nodeId)
 * @method Pipeline4x0 clusterGetkeysinslot($slot, $count)
 * @method Pipeline4x0 clusterInfo()
 * @method Pipeline4x0 clusterKeyslot($key)
 * @method Pipeline4x0 clusterMeet($ip, $port)
 * @method Pipeline4x0 clusterNodes()
 * @method Pipeline4x0 clusterReplicate($nodeId)
 * @method Pipeline4x0 clusterReset($option = null)
 * @method Pipeline4x0 clusterSaveconfig()
 * @method Pipeline4x0 clusterSetConfigEpoch($config)
 * @method Pipeline4x0 clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method Pipeline4x0 clusterSlaves($nodeId)
 * @method Pipeline4x0 clusterSlots()
 * @method Pipeline4x0 readonly()
 * @method Pipeline4x0 readwrite()
 *
 * Keys
 * @method Pipeline4x0 exists($keys)
 * -method Pipeline4x0 migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline4x0 restore($key, $ttl, $serializedValue, $replace = false)
 * @method Pipeline4x0 wait($numslaves, $timeout)
 *
 * SortedSets
 * @method Pipeline4x0 zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 *
 * Redis version 3.2
 *
 * Geo
 * @method Pipeline4x0 geoadd($key, array $members)
 * @method Pipeline4x0 geodist($key, $member1, $member2, $unit = null)
 * @method Pipeline4x0 geohash($key, $members)
 * @method Pipeline4x0 geopos($key, $members)
 * @method Pipeline4x0 georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline4x0 georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline4x0 geodel($key, $members)
 *
 * Hashes
 * @method Pipeline4x0 hstrlen($key, $field)
 *
 * Keys
 * @method Pipeline4x0 migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline4x0 touch($keys)
 *
 * Scripting
 * @method Pipeline4x0 scriptDebug($param)
 *
 * Server
 * @method Pipeline4x0 clientReply($param)
 * @method Pipeline4x0 debugHelp()
 *
 * Sets
 * @method Pipeline4x0 spop($key, $count = null)
 *
 * Strings
 * @method Pipeline4x0 bitfield($key, array $subcommands)
 *
 * Redis version 4.0
 *
 * Connection
 * @method Pipeline4x0 swapdb($db1, $db2)
 *
 * Keys
 * @method Pipeline4x0 unlink($keys)
 *
 * Memory
 * @method Pipeline4x0 memoryDoctor()
 * @method Pipeline4x0 memoryHelp()
 * @method Pipeline4x0 memoryUsage($key, $count = null)
 * @method Pipeline4x0 memoryStats()
 * @method Pipeline4x0 memoryPurge()
 * @method Pipeline4x0 memoryMallocStats()
 *
 * Server
 * @method Pipeline4x0 flushall($async = false)
 * @method Pipeline4x0 flushdb($async = false)
 * 
 */
class Pipeline4x0 extends AbstractPipeline {
    use CommandsTrait;
}
