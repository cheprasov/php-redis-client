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

use RedisClient\Command\Traits\Version2x6\CommandsTrait;
use RedisClient\Pipeline\AbstractPipeline;

/**
 * Redis version 2.6
 *
 * Connection
 * @method Pipeline2x6 auth($password)
 * @method Pipeline2x6 echo($message)
 * @method Pipeline2x6 echoMessage($message) - alias method for reversed word <echo>
 * @method Pipeline2x6 ping()
 * @method Pipeline2x6 quit()
 * @method Pipeline2x6 select($db)
 *
 * Hashes
 * @method Pipeline2x6 hdel($key, $fields)
 * @method Pipeline2x6 hexists($key, $field)
 * @method Pipeline2x6 hget($key, $field)
 * @method Pipeline2x6 hgetall($key)
 * @method Pipeline2x6 hincrby($key, $field, $increment)
 * @method Pipeline2x6 hincrbyfloat($key, $field, $increment)
 * @method Pipeline2x6 hkeys($key)
 * @method Pipeline2x6 hlen($key)
 * @method Pipeline2x6 hmget($key, $fields)
 * @method Pipeline2x6 hmset($key, array $fieldValues)
 * @method Pipeline2x6 hset($key, $field, $value)
 * @method Pipeline2x6 hsetnx($key, $field, $value)
 * @method Pipeline2x6 hvals($key)
 *
 * Keys
 * @method Pipeline2x6 del($keys)
 * @method Pipeline2x6 dump($key)
 * @method Pipeline2x6 exists($key)
 * @method Pipeline2x6 expire($key, $seconds)
 * @method Pipeline2x6 expireAt($key, $timestamp)
 * @method Pipeline2x6 keys($pattern)
 * @method Pipeline2x6 migrate($host, $port, $key, $destinationDb, $timeout)
 * @method Pipeline2x6 move($key, $db)
 * @method Pipeline2x6 object($subcommand, $arguments = null)
 * @method Pipeline2x6 persist($key)
 * @method Pipeline2x6 pexpire($key, $milliseconds)
 * @method Pipeline2x6 pexpireat($key, $millisecondsTimestamp)
 * @method Pipeline2x6 pttl($key)
 * @method Pipeline2x6 randomkey()
 * @method Pipeline2x6 rename($key, $newkey)
 * @method Pipeline2x6 renamenx($key, $newkey)
 * @method Pipeline2x6 restore($key, $ttl, $serializedValue)
 * @method Pipeline2x6 sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)
 * @method Pipeline2x6 ttl($key)
 * @method Pipeline2x6 type($key)
 *
 * Lists
 * @method Pipeline2x6 blpop($keys, $timeout)
 * @method Pipeline2x6 brpop($keys, $timeout)
 * @method Pipeline2x6 brpoplpush($source, $destination, $timeout)
 * @method Pipeline2x6 lindex($key, $index)
 * @method Pipeline2x6 linsert($key, $after = true, $pivot, $value)
 * @method Pipeline2x6 llen($key)
 * @method Pipeline2x6 lpop($key)
 * @method Pipeline2x6 lpush($key, $values)
 * @method Pipeline2x6 lpushx($key, $value)
 * @method Pipeline2x6 lrange($key, $start, $stop)
 * @method Pipeline2x6 lrem($key, $count, $value)
 * @method Pipeline2x6 lset($key, $index, $value)
 * @method Pipeline2x6 ltrim($key, $start, $stop)
 * @method Pipeline2x6 rpop($key)
 * @method Pipeline2x6 rpoplpush($source, $destination)
 * @method Pipeline2x6 rpush($key, $values)
 * @method Pipeline2x6 rpushx($key, $value)
 *
 * PubSub
 * @method Pipeline2x6 publish($channel, $message)
 * @method Pipeline2x6 punsubscribe($patterns = null)
 * @method Pipeline2x6 unsubscribe($channels)
 *
 * Scripting
 * @method Pipeline2x6 eval($script, $keys = null, $args = null)
 * @method Pipeline2x6 evalScript($script, $keys = null, $args = null) - alias method for reversed word <eval>
 * @method Pipeline2x6 evalsha($sha, $keys = null, $args = null)
 * @method Pipeline2x6 scriptExists($sha1)
 * @method Pipeline2x6 scriptFlush()
 * @method Pipeline2x6 scriptKill()
 * @method Pipeline2x6 scriptLoad($script)
 *
 * Server
 * @method Pipeline2x6 bgrewriteaof()
 * @method Pipeline2x6 bgsave()
 * @method Pipeline2x6 clientGetname()
 * @method Pipeline2x6 clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)
 * @method Pipeline2x6 clientList()
 * @method Pipeline2x6 clientSetname($connectionName)
 * @method Pipeline2x6 configGet($parameter)
 * @method Pipeline2x6 configResetstat()
 * @method Pipeline2x6 configSet($parameter, $value)
 * @method Pipeline2x6 dbsize()
 * @method Pipeline2x6 debugObject($key)
 * @method Pipeline2x6 debugSegfault()
 * @method Pipeline2x6 flushall()
 * @method Pipeline2x6 flushdb()
 * @method Pipeline2x6 info($section = null)
 * @method Pipeline2x6 lastsave()
 * @method Pipeline2x6 save()
 * @method Pipeline2x6 shutdown($save)
 * @method Pipeline2x6 slaveof($host, $port)
 * @method Pipeline2x6 slowlog($subcommand, $argument = null)
 * @method Pipeline2x6 sync()
 * @method Pipeline2x6 time()
 *
 * Sets
 * @method Pipeline2x6 sadd($key, $members)
 * @method Pipeline2x6 scard($key)
 * @method Pipeline2x6 sdiff($keys)
 * @method Pipeline2x6 sdiffstore($destination, $keys)
 * @method Pipeline2x6 sinter($keys)
 * @method Pipeline2x6 sinterstore($destination, $keys)
 * @method Pipeline2x6 sismember($key, $member)
 * @method Pipeline2x6 smembers($key)
 * @method Pipeline2x6 smove($source, $destination, $member)
 * @method Pipeline2x6 spop($key)
 * @method Pipeline2x6 srandmember($key, $count = null)
 * @method Pipeline2x6 srem($key, $members)
 * @method Pipeline2x6 sunion($keys)
 * @method Pipeline2x6 sunionstore($destination, $keys)
 *
 * SortedSets
 * @method Pipeline2x6 zadd($key, array $members)
 * @method Pipeline2x6 zcard($key)
 * @method Pipeline2x6 zcount($key, $min, $max)
 * @method Pipeline2x6 zincrby($key, $increment, $member)
 * @method Pipeline2x6 zinterstore($destination, $keys, $weights = null, $aggregate = null)
 * @method Pipeline2x6 zrange($key, $start, $stop, $withscores = false)
 * @method Pipeline2x6 zrangebyscore($key, $min, $max, $withscores = false, $limit = null)
 * @method Pipeline2x6 zrank($key, $member)
 * @method Pipeline2x6 zrem($key, $members)
 * @method Pipeline2x6 zremrangebyrank($key, $start, $stop)
 * @method Pipeline2x6 zremrangebyscore($key, $min, $max)
 * @method Pipeline2x6 zrevrange($key, $start, $stop, $withscores = false)
 * @method Pipeline2x6 zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)
 * @method Pipeline2x6 zrevrank($key, $member)
 * @method Pipeline2x6 zscore($key, $member)
 * @method Pipeline2x6 zunionstore($destination, $keys, $weights = null, $aggregate = null)
 *
 * Strings
 * @method Pipeline2x6 append($key, $value)
 * @method Pipeline2x6 bitcount($key, $start = null, $end = null)
 * @method Pipeline2x6 bitop($operation, $destkey, $keys)
 * @method Pipeline2x6 decr($key)
 * @method Pipeline2x6 decrby($key, $decrement)
 * @method Pipeline2x6 get($key)
 * @method Pipeline2x6 getbit($key, $offset)
 * @method Pipeline2x6 getrange($key, $start, $end)
 * @method Pipeline2x6 substr($key, $start, $end)
 * @method Pipeline2x6 getset($key, $value)
 * @method Pipeline2x6 incr($key)
 * @method Pipeline2x6 incrby($key, $increment)
 * @method Pipeline2x6 incrbyfloat($key, $increment)
 * @method Pipeline2x6 mget($keys)
 * @method Pipeline2x6 mset(array $keyValues)
 * @method Pipeline2x6 msetnx(array $keyValues)
 * @method Pipeline2x6 psetex($key, $milliseconds, $value)
 * @method Pipeline2x6 set($key, $value, $seconds = null, $milliseconds = null, $exist = null)
 * @method Pipeline2x6 setbit($key, $offset, $bit)
 * @method Pipeline2x6 setex($key, $seconds, $value)
 * @method Pipeline2x6 setnx($key, $value)
 * @method Pipeline2x6 setrange($key, $offset, $value)
 * @method Pipeline2x6 strlen($key)
 *
 * Transactions
 * @method Pipeline2x6 discard()
 * @method Pipeline2x6 exec()
 * @method Pipeline2x6 multi()
 * @method Pipeline2x6 unwatch()
 * @method Pipeline2x6 watch($keys)
 * 
 */
class Pipeline2x6 extends AbstractPipeline {
    use CommandsTrait;
}
