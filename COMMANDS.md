# Redis Commands

## Redis version 2.6

### Connection
- [auth($password)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php)
- [echo($message)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php)
- [echoMessage($message)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php) - alias method for `echo`
- [ping()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php)
- [quit()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php)
- [select($db)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ConnectionCommandsTrait.php)

### Hashes
- [hdel($key, $fields)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hexists($key, $field)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hget($key, $field)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hgetall($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hincrby($key, $field, $increment)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hincrbyfloat($key, $field, $increment)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hkeys($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hlen($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hmget($key, $fields)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hmset($key, array $fieldValues)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hset($key, $field, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hsetnx($key, $field, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)
- [hvals($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/HashesCommandsTrait.php)

### Keys
- [del($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [dump($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [exists($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [expire($key, $seconds)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [expireAt($key, $timestamp)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [keys($pattern)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [migrate($host, $port, $key, $destinationDb, $timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [move($key, $db)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [object($subcommand, $arguments = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [persist($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [pexpire($key, $milliseconds)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [pexpireat($key, $millisecondsTimestamp)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [pttl($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [randomkey()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [rename($key, $newkey)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [renamenx($key, $newkey)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [restore($key, $ttl, $serializedValue)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [sort($key, $pattern = null, $limit = null, $patterns = null, $asc = null, $alpha = false, $destination = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [ttl($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)
- [type($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/KeysCommandsTrait.php)

### Lists
- [blpop($keys, $timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [brpop($keys, $timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [brpoplpush($source, $destination, $timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lindex($key, $index)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [linsert($key, $after = true, $pivot, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [llen($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lpop($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lpush($key, $values)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lpushx($key, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lrange($key, $start, $stop)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lrem($key, $count, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [lset($key, $index, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [ltrim($key, $start, $stop)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [rpop($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [rpoplpush($source, $destination)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [rpush($key, $values)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)
- [rpushx($key, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ListsCommandsTrait.php)

### PubSub
- [publish($channel, $message)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/PubSubCommandsTrait.php)
- [punsubscribe($patterns = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/PubSubCommandsTrait.php)
- [unsubscribe($channels)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/PubSubCommandsTrait.php)

### Scripting
- [eval($script, $keys = null, $args = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)
- [evalScript($script, $keys = null, $args = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php) - alias method for `eval`
- [evalsha($sha, $keys = null, $args = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)
- [scriptExists($sha1)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)
- [scriptFlush()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)
- [scriptKill()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)
- [scriptLoad($script)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ScriptingCommandsTrait.php)

### Server
- [bgrewriteaof()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [bgsave()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [clientGetname()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [clientList()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [clientSetname($connectionName)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [configGet($parameter)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [configResetstat()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [configSet($parameter, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [dbsize()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [debugObject($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [debugSegfault()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [flushall()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [flushdb()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [info($section = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [lastsave()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [save()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [shutdown($save)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [slaveof($host, $port)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [slowlog($subcommand, $argument = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [sync()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)
- [time()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/ServerCommandsTrait.php)

### Sets
- [sadd($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [scard($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sdiff($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sdiffstore($destination, $keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sinter($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sinterstore($destination, $keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sismember($key, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [smembers($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [smove($source, $destination, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [spop($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [srandmember($key, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [srem($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sunion($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)
- [sunionstore($destination, $keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SetsCommandsTrait.php)

### SortedSets
- [zadd($key, array $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zcard($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zcount($key, $min, $max)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zincrby($key, $increment, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zinterstore($destination, $keys, $weights = null, $aggregate = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrange($key, $start, $stop, $withscores = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrangebyscore($key, $min, $max, $withscores = false, $limit = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrank($key, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrem($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zremrangebyrank($key, $start, $stop)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zremrangebyscore($key, $min, $max)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrevrange($key, $start, $stop, $withscores = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrevrangebyscore($key, $max, $min, $withscores = false, $limit = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zrevrank($key, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zscore($key, $member)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)
- [zunionstore($destination, $keys, $weights = null, $aggregate = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/SortedSetsCommandsTrait.php)

### Strings
- [append($key, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [bitcount($key, $start = null, $end = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [bitop($operation, $destkey, $keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [decr($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [decrby($key, $decrement)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [get($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [getbit($key, $offset)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [getrange($key, $start, $end)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [substr($key, $start, $end)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [getset($key, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [incr($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [incrby($key, $increment)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [incrbyfloat($key, $increment)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [mget($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [mset(array $keyValues)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [msetnx(array $keyValues)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [psetex($key, $milliseconds, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [set($key, $value, $seconds = null, $milliseconds = null, $exist = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [setbit($key, $offset, $bit)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [setex($key, $seconds, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [setnx($key, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [setrange($key, $offset, $value)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)
- [strlen($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/StringsCommandsTrait.php)

### Transactions
- [discard()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/TransactionsCommandsTrait.php)
- [exec()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/TransactionsCommandsTrait.php)
- [multi()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/TransactionsCommandsTrait.php)
- [unwatch()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/TransactionsCommandsTrait.php)
- [watch($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x6/TransactionsCommandsTrait.php)
## Redis version 2.8

### Connection
- [ping($message = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ConnectionCommandsTrait.php)

### Hashes
- [hscan($key, $cursor, $pattern = null, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HashesCommandsTrait.php)

### HyperLogLog
- [pfadd($key, $elements)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HyperLogLogCommandsTrait.php)
- [pfcount($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HyperLogLogCommandsTrait.php)
- [pfmerge($destkey, $sourcekeys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HyperLogLogCommandsTrait.php)
- [pfdebug($subcommand, $key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HyperLogLogCommandsTrait.php)
- [pfselftest()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/HyperLogLogCommandsTrait.php)

### Keys
- [scan($cursor, $pattern = null, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/KeysCommandsTrait.php)

### Latency
- [latencyLatest()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/LatencyCommandsTrait.php)
- [latencyHistory($eventName)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/LatencyCommandsTrait.php)
- [latencyReset($eventNames = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/LatencyCommandsTrait.php)
- [latencyGraph($eventName)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/LatencyCommandsTrait.php)
- [latencyDoctor()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/LatencyCommandsTrait.php)

### PubSub
- [pubsub($subcommand, $arguments = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/PubSubCommandsTrait.php)

### Server
- [command()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)
- [commandCount()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)
- [commandGetkeys($command)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)
- [commandInfo($commandNames)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)
- [configRewrite()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)
- [role()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/ServerCommandsTrait.php)

### Sets
- [sscan($key, $cursor, $pattern = null, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SetsCommandsTrait.php)

### SortedSets
- [zlexcount($key, $min, $max)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SortedSetsCommandsTrait.php)
- [zrangebylex($key, $min, $max, $limit = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SortedSetsCommandsTrait.php)
- [zremrangebylex($key, $min, $max)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SortedSetsCommandsTrait.php)
- [zrevrangebylex($key, $max, $min, $limit = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SortedSetsCommandsTrait.php)
- [zscan($key, $cursor, $pattern = null, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/SortedSetsCommandsTrait.php)

### Strings
- [bitpos($key, $bit, $start = null, $end = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x8/StringsCommandsTrait.php)
## Redis version 2.9

### Server
- [clientPause($timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version2x9/ServerCommandsTrait.php)
## Redis version 3.0

### Cluster
- [clusterAddslots($slots)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterCountFailureReports($nodeId)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterCountkeysinslot($slot)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterDelslots($slots)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterFailover($option = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterForget($nodeId)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterGetkeysinslot($slot, $count)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterInfo()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterKeyslot($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterMeet($ip, $port)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterNodes()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterReplicate($nodeId)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterReset($option = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterSaveconfig()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterSetConfigEpoch($config)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterSetslot($slot, $subcommand, $nodeId = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterSlaves($nodeId)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [clusterSlots()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [readonly()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)
- [readwrite()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/ClusterCommandsTrait.php)

### Keys
- [exists($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/KeysCommandsTrait.php)
- [migrate($host, $port, $key, $destinationDb, $timeout, $copy = false, $replace = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/KeysCommandsTrait.php)
- [restore($key, $ttl, $serializedValue, $replace = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/KeysCommandsTrait.php)
- [wait($numslaves, $timeout)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/KeysCommandsTrait.php)

### SortedSets
- [zadd($key, array $members, $nx = null, $ch = false, $incr = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x0/SortedSetsCommandsTrait.php)
## Redis version 3.2

### Geo
- [geoadd($key, array $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [geodist($key, $member1, $member2, $unit = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [geohash($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [geopos($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null, $storeKey = null, $storeDist = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)
- [geodel($key, $members)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/GeoCommandsTrait.php)

### Hashes
- [hstrlen($key, $field)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/HashesCommandsTrait.php)

### Keys
- [migrate($host, $port, $keys, $destinationDb, $timeout, $copy = false, $replace = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/KeysCommandsTrait.php)
- [touch($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/KeysCommandsTrait.php)

### Scripting
- [scriptDebug($param)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/ScriptingCommandsTrait.php)

### Server
- [clientReply($param)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/ServerCommandsTrait.php)
- [debugHelp()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/ServerCommandsTrait.php)

### Sets
- [spop($key, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/SetsCommandsTrait.php)

### Strings
- [bitfield($key, array $subcommands)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version3x2/StringsCommandsTrait.php)
## Redis version 4.0

### Connection
- [swapdb($db1, $db2)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/ConnectionCommandsTrait.php)

### Keys
- [unlink($keys)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/KeysCommandsTrait.php)

### Memory
- [memoryDoctor()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)
- [memoryHelp()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)
- [memoryUsage($key, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)
- [memoryStats()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)
- [memoryPurge()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)
- [memoryMallocStats()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/MemoryCommandsTrait.php)

### Server
- [flushall($async = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/ServerCommandsTrait.php)
- [flushdb($async = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version4x0/ServerCommandsTrait.php)
## Redis version 5.0

### Server
- [lolwut($param1 = null, $param2 = null, $param3 = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/ServerCommandsTrait.php)
- [replicaof($host, $port)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/ServerCommandsTrait.php)

### SortedSets
- [bzpopmax($keys, $timeout = 0)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/SortedSetsCommandsTrait.php)
- [bzpopmin($keys, $timeout = 0)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/SortedSetsCommandsTrait.php)
- [zpopmax($key, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/SortedSetsCommandsTrait.php)
- [zpopmin($key, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/SortedSetsCommandsTrait.php)

### Streams
- [xack($key, $group, $ids)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xadd($key, $id, $fieldStrings, $maxlen = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xclaim($key, $group, $consumer, $minIdleTime, $ids, $idle = null, $time = null, $retrycount = null, $force = false, $justid = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xdel($key, $ids)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xgroupCreate($key, $groupname, $id)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xgroupSetid($key, $groupname, $id)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xgroupDestroy($key, $groupname)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xgroupDelconsumer($key, $groupname, $consumername)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xgroupHelp()](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xinfo($consumersKey = null, $consumersGroup = null, $groupsKey = null, $streamKey = null, $help = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xlen($key)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xpending($key, $group, $start = null, $end = null, $count = null, $consumer = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xrange($key, $start, $end, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xread($keys, $ids, $count = null, $block = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xreadgroup($group, $consumer, $keys, $ids, $noack = false, $count = null, $block = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xrevrange($key, $end, $start, $count = null)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xtrim($key, $count, $withTilde = false)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)
- [xsetid($stream, $groupname, $id)](https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version5x0/StreamsCommandsTrait.php)

