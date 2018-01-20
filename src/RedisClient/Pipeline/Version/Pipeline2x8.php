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

use RedisClient\Command\Traits\Version2x8\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline2x8 auth($password)
 * @method Pipeline2x8 echo($message)
 * @method Pipeline2x8 echoMessage($message) - alias method for reversed word <echo>
 * -method Pipeline2x8 ping()
 * @method Pipeline2x8 quit()
 * @method Pipeline2x8 select($db)
 *
 * Hashes
 * @method Pipeline2x8 hdel($key, $fields)
 * @method Pipeline2x8 hexists($key, $field)
 * @method Pipeline2x8 hget($key, $field)
 * @method Pipeline2x8 hgetall($key)
 * @method Pipeline2x8 hincrby($key, $field, $increment)
 * @method Pipeline2x8 hincrbyfloat($key, $field, $increment)
 * @method Pipeline2x8 hkeys($key)
 * @method Pipeline2x8 hlen($key)
 * @method Pipeline2x8 hmget($key, $fields)
 * @method Pipeline2x8 hmset($key, array $fieldValues)
 * @method Pipeline2x8 hset($key, $field, $value)
 * @method Pipeline2x8 hsetnx($key, $field, $value)
 * @method Pipeline2x8 hvals($key)
 *
 * Keys
 * @method Pipeline2x8 del($keys)
 * @method Pipeline2x8 dump($key)
 * @method Pipeline2x8 exists($key)
 * @method Pipeline2x8 expire($key, $seconds)
 * @method Pipeline2x8 expireAt($key, $timestamp)
 * @method Pipeline2x8 keys($pattern)
 * @method Pipeline2x8 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline2x8 move($key, $db)
 * @method Pipeline2x8 object($subcommand, $arguments = null)
 * @method Pipeline2x8 persist($key)
 * @method Pipeline2x8 pexpire($key, $milliseconds)
 * @method Pipeline2x8 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline2x8 pttl($key)
 * @method Pipeline2x8 randomkey()
 * @method Pipeline2x8 rename($key, $newkey)
 * @method Pipeline2x8 renamenx($key, $newkey)
 * @method Pipeline2x8 restore($key, $ttl, $serializedValue)
 * @method Pipeline2x8 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline2x8 ttl($key)
 * @method Pipeline2x8 type($key)
 *
 * Lists
 * @method Pipeline2x8 blpop($keys, $timeout)
 * @method Pipeline2x8 brpop($keys, $timeout)
 * @method Pipeline2x8 brpoplpush($source, $destination, $timeout)
 * @method Pipeline2x8 lindex($key, $index)
 * @method Pipeline2x8 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline2x8 llen($key)
 * @method Pipeline2x8 lpop($key)
 * @method Pipeline2x8 lpush($key, $values)
 * @method Pipeline2x8 lpushx($key, $value)
 * @method Pipeline2x8 lrange($key, $start, $stop)
 * @method Pipeline2x8 lrem($key, $count, $value)
 * @method Pipeline2x8 lset($key, $index, $value)
 * @method Pipeline2x8 ltrim($key, $start, $stop)
 * @method Pipeline2x8 rpop($key)
 * @method Pipeline2x8 rpoplpush($source, $destination)
 * @method Pipeline2x8 rpush($key, $values)
 * @method Pipeline2x8 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline2x8 publish($channel, $message)
 * @method Pipeline2x8 punsubscribe($patterns = null)
 * @method Pipeline2x8 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline2x8 eval($script, $keys = null, $args = null)
 * @method Pipeline2x8 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline2x8 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline2x8 scriptExists($sha1)
 * @method Pipeline2x8 scriptFlush()
 * @method Pipeline2x8 scriptKill()
 * @method Pipeline2x8 scriptLoad($script)
 *
 * Server
 * @method Pipeline2x8 bgrewriteaof()
 * @method Pipeline2x8 bgsave()
 * @method Pipeline2x8 clientGetname()
 * @method Pipeline2x8 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline2x8 clientList()
 * @method Pipeline2x8 clientSetname($connectionName)
 * @method Pipeline2x8 configGet($parameter)
 * @method Pipeline2x8 configResetstat()
 * @method Pipeline2x8 configSet($parameter, $value)
 * @method Pipeline2x8 dbsize()
 * @method Pipeline2x8 debugObject($key)
 * @method Pipeline2x8 debugSegfault()
 * @method Pipeline2x8 flushall()
 * @method Pipeline2x8 flushdb()
 * @method Pipeline2x8 info($section = null)
 * @method Pipeline2x8 lastsave()
 * @method Pipeline2x8 save()
 * @method Pipeline2x8 shutdown($save)
 * @method Pipeline2x8 slaveof($host, $port)
 * @method Pipeline2x8 slowlog($subcommand, $argument = null)
 * @method Pipeline2x8 sync()
 * @method Pipeline2x8 time()
 *
 * Sets
 * @method Pipeline2x8 sadd($key, $members)
 * @method Pipeline2x8 scard($key)
 * @method Pipeline2x8 sdiff($keys)
 * @method Pipeline2x8 sdiffstore($destination, $keys)
 * @method Pipeline2x8 sinter($keys)
 * @method Pipeline2x8 sinterstore($destination, $keys)
 * @method Pipeline2x8 sismember($key, $member)
 * @method Pipeline2x8 smembers($key)
 * @method Pipeline2x8 smove($source, $destination, $member)
 * @method Pipeline2x8 spop($key)
 * @method Pipeline2x8 srandmember($key, $count = null)
 * @method Pipeline2x8 srem($key, $members)
 * @method Pipeline2x8 sunion($keys)
 * @method Pipeline2x8 sunionstore($destination, $keys)
 *
 * SortedSets
 * @method Pipeline2x8 zadd($key, array $members)
 * @method Pipeline2x8 zcard($key)
 * @method Pipeline2x8 zcount($key, $min, $max)
 * @method Pipeline2x8 zincrby($key, $increment, $member)
 * @method Pipeline2x8 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline2x8 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline2x8 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline2x8 zrank($key, $member)
 * @method Pipeline2x8 zrem($key, $members)
 * @method Pipeline2x8 zremrangebyrank($key, $start, $stop)
 * @method Pipeline2x8 zremrangebyscore($key, $min, $max)
 * @method Pipeline2x8 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline2x8 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline2x8 zrevrank($key, $member)
 * @method Pipeline2x8 zscore($key, $member)
 * @method Pipeline2x8 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline2x8 append($key, $value)
 * @method Pipeline2x8 bitcount($key, $start = null, $end = null)
 * @method Pipeline2x8 bitop($operation, $destkey, $keys)
 * @method Pipeline2x8 decr($key)
 * @method Pipeline2x8 decrby($key, $decrement)
 * @method Pipeline2x8 get($key)
 * @method Pipeline2x8 getbit($key, $offset)
 * @method Pipeline2x8 getrange($key, $start, $end)
 * @method Pipeline2x8 substr($key, $start, $end)
 * @method Pipeline2x8 getset($key, $value)
 * @method Pipeline2x8 incr($key)
 * @method Pipeline2x8 incrby($key, $increment)
 * @method Pipeline2x8 incrbyfloat($key, $increment)
 * @method Pipeline2x8 mget($keys)
 * @method Pipeline2x8 mset(array $keyValues)
 * @method Pipeline2x8 msetnx(array $keyValues)
 * @method Pipeline2x8 psetex($key, $milliseconds, $value)
 * @method Pipeline2x8 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline2x8 setbit($key, $offset, $bit)
 * @method Pipeline2x8 setex($key, $seconds, $value)
 * @method Pipeline2x8 setnx($key, $value)
 * @method Pipeline2x8 setrange($key, $offset, $value)
 * @method Pipeline2x8 strlen($key)
 *
 * Transactions
 * @method Pipeline2x8 discard()
 * @method Pipeline2x8 exec()
 * @method Pipeline2x8 multi()
 * @method Pipeline2x8 unwatch()
 * @method Pipeline2x8 watch($keys)
 *
 * Redis version 2.8
 *
 * Connection
 * @method Pipeline2x8 ping($message = null)
 *
 * Hashes
 * @method Pipeline2x8 hscan($key, $cursor, $pattern = null, $count = null)
 *
 * HyperLogLog
 * @method Pipeline2x8 pfadd($key, $elements)
 * @method Pipeline2x8 pfcount($keys)
 * @method Pipeline2x8 pfmerge($destkey, $sourcekeys)
 * @method Pipeline2x8 pfdebug($subcommand, $key)
 * @method Pipeline2x8 pfselftest()
 *
 * Keys
 * @method Pipeline2x8 scan($cursor, $pattern = null, $count = null)
 *
 * Latency
 * @method Pipeline2x8 latencyLatest()
 * @method Pipeline2x8 latencyHistory($eventName)
 * @method Pipeline2x8 latencyReset($eventNames = null)
 * @method Pipeline2x8 latencyGraph($eventName)
 * @method Pipeline2x8 latencyDoctor()
 *
 * PubSub
 * @method Pipeline2x8 pubsub($subcommand, $arguments = null)
 *
 * Server
 * @method Pipeline2x8 command()
 * @method Pipeline2x8 commandCount()
 * @method Pipeline2x8 commandGetkeys($command)
 * @method Pipeline2x8 commandInfo($commandNames)
 * @method Pipeline2x8 configRewrite()
 * @method Pipeline2x8 role()
 *
 * Sets
 * @method Pipeline2x8 sscan($key, $cursor, $pattern = null, $count = null)
 *
 * SortedSets
 * @method Pipeline2x8 zlexcount($key, $min, $max)
 * @method Pipeline2x8 zrangebylex($key, $min, $max, $limit = null)
 * @method Pipeline2x8 zremrangebylex($key, $min, $max)
 * @method Pipeline2x8 zrevrangebylex($key, $max, $min, $limit = null)
 * @method Pipeline2x8 zscan($key, $cursor, $pattern = null, $count = null)
 *
 * Strings
 * @method Pipeline2x8 bitpos($key, $bit, $start = null, $end = null)
 * 
 */
class Pipeline2x8 extends AbstractPipeline {
    use CommandsTrait;
}
