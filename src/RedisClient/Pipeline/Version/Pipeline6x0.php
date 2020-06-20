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
 * @method Pipeline6x0 auth($password)
 * @method Pipeline6x0 echo($message)
 * @method Pipeline6x0 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline6x0 ping()
 * @method Pipeline6x0 quit()
 * @method Pipeline6x0 select($db)
 *
 * Hashes
 * @method Pipeline6x0 hdel($key, $fields)
 * @method Pipeline6x0 hexists($key, $field)
 * @method Pipeline6x0 hget($key, $field)
 * @method Pipeline6x0 hgetall($key)
 * @method Pipeline6x0 hincrby($key, $field, $increment)
 * @method Pipeline6x0 hincrbyfloat($key, $field, $increment)
 * @method Pipeline6x0 hkeys($key)
 * @method Pipeline6x0 hlen($key)
 * @method Pipeline6x0 hmget($key, $fields)
 * @method Pipeline6x0 hmset($key, array $fieldValues)
 * @method Pipeline6x0 hset($key, $field, $value)
 * @method Pipeline6x0 hsetnx($key, $field, $value)
 * @method Pipeline6x0 hvals($key)
 *
 * Keys
 * @method Pipeline6x0 del($keys)
 * @method Pipeline6x0 dump($key)
 * -method Pipeline6x0 exists($key)
 * @method Pipeline6x0 expire($key, $seconds)
 * @method Pipeline6x0 expireAt($key, $timestamp)
 * @method Pipeline6x0 keys($pattern)
 * -method Pipeline6x0 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline6x0 move($key, $db)
 * @method Pipeline6x0 object($subcommand, $arguments = null)
 * @method Pipeline6x0 persist($key)
 * @method Pipeline6x0 pexpire($key, $milliseconds)
 * @method Pipeline6x0 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline6x0 pttl($key)
 * @method Pipeline6x0 randomkey()
 * @method Pipeline6x0 rename($key, $newkey)
 * @method Pipeline6x0 renamenx($key, $newkey)
 * -method Pipeline6x0 restore($key, $ttl, $serializedValue)
 * @method Pipeline6x0 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline6x0 ttl($key)
 * @method Pipeline6x0 type($key)
 *
 * Lists
 * @method Pipeline6x0 blpop($keys, $timeout)
 * @method Pipeline6x0 brpop($keys, $timeout)
 * @method Pipeline6x0 brpoplpush($source, $destination, $timeout)
 * @method Pipeline6x0 lindex($key, $index)
 * @method Pipeline6x0 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline6x0 llen($key)
 * @method Pipeline6x0 lpop($key)
 * @method Pipeline6x0 lpush($key, $values)
 * @method Pipeline6x0 lpushx($key, $value)
 * @method Pipeline6x0 lrange($key, $start, $stop)
 * @method Pipeline6x0 lrem($key, $count, $value)
 * @method Pipeline6x0 lset($key, $index, $value)
 * @method Pipeline6x0 ltrim($key, $start, $stop)
 * @method Pipeline6x0 rpop($key)
 * @method Pipeline6x0 rpoplpush($source, $destination)
 * @method Pipeline6x0 rpush($key, $values)
 * @method Pipeline6x0 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline6x0 publish($channel, $message)
 * @method Pipeline6x0 punsubscribe($patterns = null)
 * @method Pipeline6x0 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline6x0 eval($script, $keys = null, $args = null)
 * @method Pipeline6x0 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline6x0 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline6x0 scriptExists($sha1)
 * @method Pipeline6x0 scriptFlush()
 * @method Pipeline6x0 scriptKill()
 * @method Pipeline6x0 scriptLoad($script)
 *
 * Server
 * @method Pipeline6x0 bgrewriteaof()
 * @method Pipeline6x0 bgsave()
 * @method Pipeline6x0 clientGetname()
 * @method Pipeline6x0 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline6x0 clientList()
 * @method Pipeline6x0 clientSetname($connectionName)
 * @method Pipeline6x0 configGet($parameter)
 * @method Pipeline6x0 configResetstat()
 * @method Pipeline6x0 configSet($parameter, $value)
 * @method Pipeline6x0 dbsize()
 * @method Pipeline6x0 debugObject($key)
 * @method Pipeline6x0 debugSegfault()
 * -method Pipeline6x0 flushall()
 * -method Pipeline6x0 flushdb()
 * @method Pipeline6x0 info($section = null)
 * @method Pipeline6x0 lastsave()
 * @method Pipeline6x0 save()
 * @method Pipeline6x0 shutdown($save)
 * @method Pipeline6x0 slaveof($host, $port)
 * @method Pipeline6x0 slowlog($subcommand, $argument = null)
 * @method Pipeline6x0 sync()
 * @method Pipeline6x0 time()
 *
 * Sets
 * @method Pipeline6x0 sadd($key, $members)
 * @method Pipeline6x0 scard($key)
 * @method Pipeline6x0 sdiff($keys)
 * @method Pipeline6x0 sdiffstore($destination, $keys)
 * @method Pipeline6x0 sinter($keys)
 * @method Pipeline6x0 sinterstore($destination, $keys)
 * @method Pipeline6x0 sismember($key, $member)
 * @method Pipeline6x0 smembers($key)
 * @method Pipeline6x0 smove($source, $destination, $member)
 * -method Pipeline6x0 spop($key)
 * @method Pipeline6x0 srandmember($key, $count = null)
 * @method Pipeline6x0 srem($key, $members)
 * @method Pipeline6x0 sunion($keys)
 * @method Pipeline6x0 sunionstore($destination, $keys)
 *
 * SortedSets
 * -method Pipeline6x0 zadd($key, array $members)
 * @method Pipeline6x0 zcard($key)
 * @method Pipeline6x0 zcount($key, $min, $max)
 * @method Pipeline6x0 zincrby($key, $increment, $member)
 * @method Pipeline6x0 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline6x0 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline6x0 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline6x0 zrank($key, $member)
 * @method Pipeline6x0 zrem($key, $members)
 * @method Pipeline6x0 zremrangebyrank($key, $start, $stop)
 * @method Pipeline6x0 zremrangebyscore($key, $min, $max)
 * @method Pipeline6x0 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline6x0 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline6x0 zrevrank($key, $member)
 * @method Pipeline6x0 zscore($key, $member)
 * @method Pipeline6x0 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline6x0 append($key, $value)
 * @method Pipeline6x0 bitcount($key, $start = null, $end = null)
 * @method Pipeline6x0 bitop($operation, $destkey, $keys)
 * @method Pipeline6x0 decr($key)
 * @method Pipeline6x0 decrby($key, $decrement)
 * @method Pipeline6x0 get($key)
 * @method Pipeline6x0 getbit($key, $offset)
 * @method Pipeline6x0 getrange($key, $start, $end)
 * @method Pipeline6x0 substr($key, $start, $end)
 * @method Pipeline6x0 getset($key, $value)
 * @method Pipeline6x0 incr($key)
 * @method Pipeline6x0 incrby($key, $increment)
 * @method Pipeline6x0 incrbyfloat($key, $increment)
 * @method Pipeline6x0 mget($keys)
 * @method Pipeline6x0 mset(array $keyValues)
 * @method Pipeline6x0 msetnx(array $keyValues)
 * @method Pipeline6x0 psetex($key, $milliseconds, $value)
 * @method Pipeline6x0 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline6x0 setbit($key, $offset, $bit)
 * @method Pipeline6x0 setex($key, $seconds, $value)
 * @method Pipeline6x0 setnx($key, $value)
 * @method Pipeline6x0 setrange($key, $offset, $value)
 * @method Pipeline6x0 strlen($key)
 *
 * Transactions
 * @method Pipeline6x0 discard()
 * @method Pipeline6x0 exec()
 * @method Pipeline6x0 multi()
 * @method Pipeline6x0 unwatch()
 * @method Pipeline6x0 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline6x0 ping($message = null)
 *
 * Hashes
 * @method Pipeline6x0 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline6x0 pfadd($key, $elements)
 * @method Pipeline6x0 pfcount($keys)
 * @method Pipeline6x0 pfmerge($destkey, $sourcekeys)
 * @method Pipeline6x0 pfdebug($subcommand, $key)
 * @method Pipeline6x0 pfselftest()
 *
 * Keys
 * @method Pipeline6x0 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline6x0 latencyLatest()
 * @method Pipeline6x0 latencyHistory($eventName)
 * @method Pipeline6x0 latencyReset($eventNames = null)
 * @method Pipeline6x0 latencyGraph($eventName)
 * @method Pipeline6x0 latencyDoctor()
 *
 * PubSub
 * @method Pipeline6x0 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline6x0 command()
 * @method Pipeline6x0 commandCount()
 * @method Pipeline6x0 commandGetkeys($command)
 * @method Pipeline6x0 commandInfo($commandNames)
 * @method Pipeline6x0 configRewrite()
 * @method Pipeline6x0 role()
 *
 * Sets
 * @method Pipeline6x0 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline6x0 zlexcount($key, $min, $max)
 * @method Pipeline6x0 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline6x0 zremrangebylex($key, $min, $max)
 * @method Pipeline6x0 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline6x0 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline6x0 bitpos($key, $bit, $start = null, $end = null)
 *
 * Redis version 2.9
 *
 * Server
 * @method Pipeline6x0 clientPause($timeout)
 *
 * Redis version 3.0
 *
 * Cluster
 * @method Pipeline6x0 clusterAddslots($slots)
 * @method Pipeline6x0 clusterCountFailureReports($nodeId)
 * @method Pipeline6x0 clusterCountkeysinslot($slot)
 * @method Pipeline6x0 clusterDelslots($slots)
 * @method Pipeline6x0 clusterFailover($option = null)
 * @method Pipeline6x0 clusterForget($nodeId)
 * @method Pipeline6x0 clusterGetkeysinslot($slot, $count)
 * @method Pipeline6x0 clusterInfo()
 * @method Pipeline6x0 clusterKeyslot($key)
 * @method Pipeline6x0 clusterMeet($ip, $port)
 * @method Pipeline6x0 clusterNodes()
 * @method Pipeline6x0 clusterReplicate($nodeId)
 * @method Pipeline6x0 clusterReset($option = null)
 * @method Pipeline6x0 clusterSaveconfig()
 * @method Pipeline6x0 clusterSetConfigEpoch($config)
 * @method Pipeline6x0 clusterSetslot($slot, $subcommand, $nodeId = null)
 * @method Pipeline6x0 clusterSlaves($nodeId)
 * @method Pipeline6x0 clusterSlots()
 * @method Pipeline6x0 readonly()
 * @method Pipeline6x0 readwrite()
 *
 * Keys
 * @method Pipeline6x0 exists($keys)
 * -method Pipeline6x0 migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline6x0 restore($key, $ttl, $serializedValue, $replace = false)
 * @method Pipeline6x0 wait($numslaves, $timeout)
 *
 * SortedSets
 * @method Pipeline6x0 zadd($key, array $members, $nx = null, $ch = false, $incr = false)
 *
 * Redis version 3.2
 *
 * Geo
 * @method Pipeline6x0 geoadd($key, array $members)
 * @method Pipeline6x0 geodist($key, $member1, $member2, $unit = null)
 * @method Pipeline6x0 geohash($key, $members)
 * @method Pipeline6x0 geopos($key, $members)
 * @method Pipeline6x0 georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline6x0 georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)
 * @method Pipeline6x0 geodel($key, $members)
 *
 * Hashes
 * @method Pipeline6x0 hstrlen($key, $field)
 *
 * Keys
 * @method Pipeline6x0 migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false)
 * @method Pipeline6x0 touch($keys)
 *
 * Scripting
 * @method Pipeline6x0 scriptDebug($param)
 *
 * Server
 * @method Pipeline6x0 clientReply($param)
 * @method Pipeline6x0 debugHelp()
 *
 * Sets
 * @method Pipeline6x0 spop($key, $count = null)
 *
 * Strings
 * @method Pipeline6x0 bitfield($key, array $subcommands)
 *
 * Redis version 4.0
 *
 * Connection
 * @method Pipeline6x0 swapdb($db1, $db2)
 *
 * Keys
 * @method Pipeline6x0 unlink($keys)
 *
 * Memory
 * @method Pipeline6x0 memoryDoctor()
 * @method Pipeline6x0 memoryHelp()
 * @method Pipeline6x0 memoryUsage($key, $count = null)
 * @method Pipeline6x0 memoryStats()
 * @method Pipeline6x0 memoryPurge()
 * @method Pipeline6x0 memoryMallocStats()
 *
 * Server
 * @method Pipeline6x0 flushall($async = false)
 * @method Pipeline6x0 flushdb($async = false)
 *
 * Redis version 5.0
 *
 * Connection
 * @method Pipeline6x0 clientId()
 * @method Pipeline6x0 clientUnblock($clientId, $timeoutOrError = null)
 *
 * Server
 * @method Pipeline6x0 lolwut($param1 = null, $param2 = null, $param3 = null)
 * @method Pipeline6x0 replicaof($host, $port)
 *
 * SortedSets
 * @method Pipeline6x0 bzpopmax($keys, $timeout = 0)
 * @method Pipeline6x0 bzpopmin($keys, $timeout = 0)
 * @method Pipeline6x0 zpopmax($key, $count = null)
 * @method Pipeline6x0 zpopmin($key, $count = null)
 *
 * Streams
 * @method Pipeline6x0 xack($key, $group, $ids)
 * @method Pipeline6x0 xadd($key, $id, $fieldStrings, $maxlen = null)
 * @method Pipeline6x0 xclaim($key, $group, $consumer, $minIdleTime, $ids, $idle = null, $time = null, $retrycount = null, $force = false, $justid = false)
 * @method Pipeline6x0 xdel($key, $ids)
 * @method Pipeline6x0 xgroupCreate($key, $groupname, $id)
 * @method Pipeline6x0 xgroupSetid($key, $groupname, $id)
 * @method Pipeline6x0 xgroupDestroy($key, $groupname)
 * @method Pipeline6x0 xgroupDelconsumer($key, $groupname, $consumername)
 * @method Pipeline6x0 xgroupHelp()
 * -method Pipeline6x0 xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $help = false)
 * @method Pipeline6x0 xlen($key)
 * @method Pipeline6x0 xpending($key, $group, $start = null, $end = null, $count = null, $consumer = null)
 * @method Pipeline6x0 xrange($key, $start, $end, $count = null)
 * @method Pipeline6x0 xread($keys, $ids, $count = null, $block = null)
 * @method Pipeline6x0 xreadgroup($group, $consumer, $keys, $ids, $noack = false, $count = null, $block = null)
 * @method Pipeline6x0 xrevrange($key, $end, $start, $count = null)
 * @method Pipeline6x0 xtrim($key, $count, $withTilde = false)
 * @method Pipeline6x0 xsetid($stream, $groupname, $id)
 *
 * Redis version 6.0
 *
 * Connection
 * @method Pipeline6x0 clientCaching($isEnabled)
 * @method Pipeline6x0 clientCetredir()
 * @method Pipeline6x0 clientTracking($isEnabled, $redirectClientId = null, $prefixes = null, $bcast = null, $optin = null, $optout = null, $noloop = null)
 * @method Pipeline6x0 hello($protover, $username = null, $password = null, $clientName = null)
 *
 * Lists
 * @method Pipeline6x0 lpos($key, $element, $rank = null, $numMatches = null, $len = null)
 *
 * Server
 * @method Pipeline6x0 aclCat($categoryName = null)
 * @method Pipeline6x0 aclDeluser($usernames)
 * @method Pipeline6x0 aclGenpass($bits = null)
 * @method Pipeline6x0 aclGetuser($username)
 * @method Pipeline6x0 aclHelp()
 * @method Pipeline6x0 aclList()
 * @method Pipeline6x0 aclLog($count = null)
 * @method Pipeline6x0 aclLogReset()
 * @method Pipeline6x0 aclSave()
 * @method Pipeline6x0 aclSetuser($username, $rules = null)
 * @method Pipeline6x0 aclUsers()
 * @method Pipeline6x0 aclWhoami()
 *
 * Streams
 * @method Pipeline6x0 xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $streamFull = null, $help = false)
 *
 * Strings
 * @method Pipeline6x0 stralgoLcs(array $algoSpecificArguments)
 * 
 */
class Pipeline6x0 extends AbstractPipeline {
    use CommandsTrait;
}
