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
namespace RedisClient\Command\Traits\Version2x8;

/**
 * HyperLogLog Commands
 * @link http://redis.io/commands#hyperloglog
 */
trait HyperLogLogCommandsTrait {

    /**
     * PFADD key element [element ...]
     * Available since 2.8.9.
     * Time complexity: O(1) to add every element.
     * @link http://redis.io/commands/pfadd
     *
     * @param string $key
     * @param string|string[] $elements
     * @return int
     */
    public function pfadd($key, $elements) {
        return $this->returnCommand(['PFADD'], $key, [$key, (array)$elements]);
    }

    /**
     * PFCOUNT key [key ...]
     * Available since 2.8.9.
     * Time complexity: O(1) with every small average constant times when called with a single key.
     * O(N) with N being the number of keys, and much bigger constant times, when called with multiple keys.
     * @link http://redis.io/commands/pfcount
     *
     * @param string|string[] $keys
     * @return int
     */
    public function pfcount($keys) {
        $keys = (array)$keys;
        return $this->returnCommand(['PFCOUNT'], $keys, $keys);
    }

    /**
     * PFMERGE destkey sourcekey [sourcekey ...]
     * Available since 2.8.9.
     * Time complexity: O(N) to merge N HyperLogLogs, but with high constant times.
     * @link http://redis.io/commands/pfmerge
     *
     * @param string $destkey
     * @param string|string[] $sourcekeys
     * @return bool The command just returns True.
     */
    public function pfmerge($destkey, $sourcekeys) {
        $keys = (array)$sourcekeys;
        array_unshift($keys, $destkey);
        return $this->returnCommand(['PFMERGE'], $keys, $keys);
    }

//    /**
//     * PFDEBUG <subcommand> <key> ... args ...
//     * Available since 2.8.9.
//     * @link http://download.redis.io/redis-stable/src/hyperloglog.c
//     * @debug
//     * @deprecated only for debug
//     *
//     * @param string $subcommand GETREG|DECODE|ENCODING|TODENSE
//     * @param string $key
//     * @return mixed
//     */
//    public function pfdebug($subcommand, $key) {
//        return $this->returnCommand(['PFDEBUG'], $key, [$subcommand, $key]);
//    }

//    /**
//     * PFSELFTEST
//     * Available since 2.8.9.
//     * @link http://download.redis.io/redis-stable/src/hyperloglog.c
//     * @debug
//     * @deprecated only for debug
//     *
//     * @return mixed
//     */
//    public function pfselftest() {
//        return $this->returnCommand(['PFSELFTEST']);
//    }

}
