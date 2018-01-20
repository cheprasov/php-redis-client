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

use RedisClient\Command\Traits\Version3x2\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline3x2 auth($password)
 * @method Pipeline3x2 echo($message)
 * @method Pipeline3x2 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline3x2 ping()
 * @method Pipeline3x2 quit()
 * @method Pipeline3x2 select($db)
 *
 * Hashes
 * @method Pipeline3x2 hdel($key, $fields)
 * @method Pipeline3x2 hexists($key, $field)
 * @method Pipeline3x2 hget($key, $field)
 * @method Pipeline3x2 hgetall($key)
 * @method Pipeline3x2 hincrby($key, $field, $increment)
 * @method Pipeline3x2 hincrbyfloat($key, $field, $increment)
 * @method Pipeline3x2 hkeys($key)
 * @method Pipeline3x2 hlen($key)
 * @method Pipeline3x2 hmget($key, $fields)
 * @method Pipeline3x2 hmset($key, array $fieldValues)
 * @method Pipeline3x2 hset($key, $field, $value)
 * @method Pipeline3x2 hsetnx($key, $field, $value)
 * @method Pipeline3x2 hvals($key)
 *
 * Keys
 * @method Pipeline3x2 del($keys)
 * @method Pipeline3x2 dump($key)
 * -method Pipeline3x2 exists($key)
 * @method Pipeline3x2 expire($key, $seconds)
 * @method Pipeline3x2 expireAt($key, $timestamp)
 * @method Pipeline3x2 keys($pattern)
 * -method Pipeline3x2 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline3x2 move($key, $db)
 * @method Pipeline3x2 object($subcommand, $arguments = null)
 * @method Pipeline3x2 persist($key)
 * @method Pipeline3x2 pexpire($key, $milliseconds)
 * @method Pipeline3x2 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline3x2 pttl($key)
 * @method Pipeline3x2 randomkey()
 * @method Pipeline3x2 rename($key, $newkey)
 * @method Pipeline3x2 renamenx($key, $newkey)
 * -method Pipeline3x2 restore($key, $ttl, $serializedValue)
 * @method Pipeline3x2 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline3x2 ttl($key)
 * @method Pipeline3x2 type($key)
 *
 * Lists
 * @method Pipeline3x2 blpop($keys, $timeout)
 * @method Pipeline3x2 brpop($keys, $timeout)
 * @method Pipeline3x2 brpoplpush($source, $destination, $timeout)
 * @method Pipeline3x2 lindex($key, $index)
 * @method Pipeline3x2 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline3x2 llen($key)
 * @method Pipeline3x2 lpop($key)
 * @method Pipeline3x2 lpush($key, $values)
 * @method Pipeline3x2 lpushx($key, $value)
 * @method Pipeline3x2 lrange($key, $start, $stop)
 * @method Pipeline3x2 lrem($key, $count, $value)
 * @method Pipeline3x2 lset($key, $index, $value)
 * @method Pipeline3x2 ltrim($key, $start, $stop)
 * @method Pipeline3x2 rpop($key)
 * @method Pipeline3x2 rpoplpush($source, $destination)
 * @method Pipeline3x2 rpush($key, $values)
 * @method Pipeline3x2 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline3x2 publish($channel, $message)
 * @method Pipeline3x2 punsubscribe($patterns = null)
 * @method Pipeline3x2 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline3x2 eval($script, $keys = null, $args = null)
 * @method Pipeline3x2 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline3x2 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline3x2 scriptExists($sha1)
 * @method Pipeline3x2 scriptFlush()
 * @method Pipeline3x2 scriptKill()
 * @method Pipeline3x2 scriptLoad($script)
 *
 * Server
 * @method Pipeline3x2 bgrewriteaof()
 * @method Pipeline3x2 bgsave()
 * @method Pipeline3x2 clientGetname()
 * @method Pipeline3x2 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline3x2 clientList()
 * @method Pipeline3x2 clientSetname($connectionName)
 * @method Pipeline3x2 configGet($parameter)
 * @method Pipeline3x2 configResetstat()
 * @method Pipeline3x2 configSet($parameter, $value)
 * @method Pipeline3x2 dbsize()
 * @method Pipeline3x2 debugObject($key)
 * @method Pipeline3x2 debugSegfault()
 * @method Pipeline3x2 flushall()
 * @method Pipeline3x2 flushdb()
 * @method Pipeline3x2 info($section = null)
 * @method Pipeline3x2 lastsave()
 * @method Pipeline3x2 save()
 * @method Pipeline3x2 shutdown($save)
 * @method Pipeline3x2 slaveof($host, $port)
 * @method Pipeline3x2 slowlog($subcommand, $argument = null)
 * @method Pipeline3x2 sync()
 * @method Pipeline3x2 time()
 *
 * Sets
 * @method Pipeline3x2 sadd($key, $members)
 * @method Pipeline3x2 scard($key)
 * @method Pipeline3x2 sdiff($keys)
 * @method Pipeline3x2 sdiffstore($destination, $keys)
 * @method Pipeline3x2 sinter($keys)
 * @method Pipeline3x2 sinterstore($destination, $keys)
 * @method Pipeline3x2 sismember($key, $member)
 * @method Pipeline3x2 smembers($key)
 * @method Pipeline3x2 smove($source, $destination, $member)
 * -method Pipeline3x2 spop($key)
 * @method Pipeline3x2 srandmember($key, $count = null)
 * @method Pipeline3x2 srem($key, $members)
 * @method Pipeline3x2 sunion($keys)
 * @method Pipeline3x2 sunionstore($destination, $keys)
 *
 * SortedSets
 * -method Pipeline3x2 zadd($key, array $members)
 * @method Pipeline3x2 zcard($key)
 * @method Pipeline3x2 zcount($key, $min, $max)
 * @method Pipeline3x2 zincrby($key, $increment, $member)
 * @method Pipeline3x2 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline3x2 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline3x2 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline3x2 zrank($key, $member)
 * @method Pipeline3x2 zrem($key, $members)
 * @method Pipeline3x2 zremrangebyrank($key, $start, $stop)
 * @method Pipeline3x2 zremrangebyscore($key, $min, $max)
 * @method Pipeline3x2 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline3x2 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline3x2 zrevrank($key, $member)
 * @method Pipeline3x2 zscore($key, $member)
 * @method Pipeline3x2 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline3x2 append($key, $value)
 * @method Pipeline3x2 bitcount($key, $start = null, $end = null)
 * @method Pipeline3x2 bitop($operation, $destkey, $keys)
 * @method Pipeline3x2 decr($key)
 * @method Pipeline3x2 decrby($key, $decrement)
 * @method Pipeline3x2 get($key)
 * @method Pipeline3x2 getbit($key, $offset)
 * @method Pipeline3x2 getrange($key, $start, $end)
 * @method Pipeline3x2 substr($key, $start, $end)
 * @method Pipeline3x2 getset($key, $value)
 * @method Pipeline3x2 incr($key)
 * @method Pipeline3x2 incrby($key, $increment)
 * @method Pipeline3x2 incrbyfloat($key, $increment)
 * @method Pipeline3x2 mget($keys)
 * @method Pipeline3x2 mset(array $keyValues)
 * @method Pipeline3x2 msetnx(array $keyValues)
 * @method Pipeline3x2 psetex($key, $milliseconds, $value)
 * @method Pipeline3x2 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline3x2 setbit($key, $offset, $bit)
 * @method Pipeline3x2 setex($key, $seconds, $value)
 * @method Pipeline3x2 setnx($key, $value)
 * @method Pipeline3x2 setrange($key, $offset, $value)
 * @method Pipeline3x2 strlen($key)
 *
 * Transactions
 * @method Pipeline3x2 discard()
 * @method Pipeline3x2 exec()
 * @method Pipeline3x2 multi()
 * @method Pipeline3x2 unwatch()
 * @method Pipeline3x2 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline3x2 ping($message = null)
 *
 * Hashes
 * @method Pipeline3x2 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline3x2 pfadd($key, $elements)
 * @method Pipeline3x2 pfcount($keys)
 * @method Pipeline3x2 pfmerge($destkey, $sourcekeys)
 * @method Pipeline3x2 pfdebug($subcommand, $key)
 * @method Pipeline3x2 pfselftest()
 *
 * Keys
 * @method Pipeline3x2 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline3x2 latencyLatest()
 * @method Pipeline3x2 latencyHistory($eventName)
 * @method Pipeline3x2 latencyReset($eventNames = null)
 * @method Pipeline3x2 latencyGraph($eventName)
 * @method Pipeline3x2 latencyDoctor()
 *
 * PubSub
 * @method Pipeline3x2 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline3x2 command()
 * @method Pipeline3x2 commandCount()
 * @method Pipeline3x2 commandGetkeys($command)
 * @method Pipeline3x2 commandInfo($commandNames)
 * @method Pipeline3x2 configRewrite()
 * @method Pipeline3x2 role()
 *
 * Sets
 * @method Pipeline3x2 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline3x2 zlexcount($key, $min, $max)
 * @method Pipeline3x2 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline3x2 zremrangebylex($key, $min, $max)
 * @method Pipeline3x2 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline3x2 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline3x2 bitpos($key, $bit, $start = null, $end = null)
 *
 * Redis version 2.9
 *
 * Server
 * @method Pipeline3x2 clientPause($timeout)
 *
 * Redis version 3.0
 *
 * Cluster
 * @method Pipeline3x2 clusterAddslots($slots)
 * @method Pipeline3x2 clusterCountFailureReports($nodeId)
 * @method Pipeline3x2 clusterCountkeysinslot($slot)
 * @method Pipeline3x2 clusterDelslots($slots)
 * @method Pipeline3x2 clusterFailover($option = null)
 * @method Pipeline3x2 clusterForget($nodeId)
 * @method Pipeline3x2 clusterGetkeysinslot($slot, $count)
 * @method Pipeline3x2 clusterInfo()
 * @method Pipeline3x2 clusterKeyslot($key)
 * @method Pipeline3x2 clusterMeet($ip, $port)
 * @method Pipeline3x2 clusterNodes()
 * @method Pipeline3x2 clusterReplicate($nodeId)
 * @method Pipeline3x2 clusterReset($option = null)
 * @method Pipeline3x2 clusterSaveconfig()
 * @method Pipeline3x2 clusterSetConfigEpoch($config)
 * @method Pipeline3x2 clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method Pipeline3x2 clusterSlaves($nodeId)
 * @method Pipeline3x2 clusterSlots()
 * @method Pipeline3x2 readonly()
 * @method Pipeline3x2 readwrite()
 *
 * Keys
 * @method Pipeline3x2 exists($keys)
 * -method Pipeline3x2 migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline3x2 restore($key, $ttl, $serializedValue, $replace = false)
 * @method Pipeline3x2 wait($numslaves, $timeout)
 *
 * SortedSets
 * @method Pipeline3x2 zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 *
 * Redis version 3.2
 *
 * Geo
 * @method Pipeline3x2 geoadd($key, array $members)
 * @method Pipeline3x2 geodist($key, $member1, $member2, $unit = null)
 * @method Pipeline3x2 geohash($key, $members)
 * @method Pipeline3x2 geopos($key, $members)
 * @method Pipeline3x2 georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline3x2 georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline3x2 geodel($key, $members)
 *
 * Hashes
 * @method Pipeline3x2 hstrlen($key, $field)
 *
 * Keys
 * @method Pipeline3x2 migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline3x2 touch($keys)
 *
 * Scripting
 * @method Pipeline3x2 scriptDebug($param)
 *
 * Server
 * @method Pipeline3x2 clientReply($param)
 * @method Pipeline3x2 debugHelp()
 *
 * Sets
 * @method Pipeline3x2 spop($key, $count = null)
 *
 * Strings
 * @method Pipeline3x2 bitfield($key, array $subcommands)
 * 
 */
class Pipeline3x2 extends AbstractPipeline {
    use CommandsTrait;
}
