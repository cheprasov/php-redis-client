<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

/**
 * HyperLogLog
 * @link http://redis.io/commands#hyperloglog
 */
trait HyperLogLogCommandsTrait {

    /**
     * PFADD key element [element ...]
     * Available since 2.8.9.
     * Time complexity: O(1) to add every element.
     *
     * @param string $key
     * @param string|string[] $elements
     * @return int
     */
    public function pfadd($key, $elements) {
        return $this->returnCommand(
            new Command('PFADD', [
                Parameter::key($key),
                Parameter::strings($elements)
            ])
        );
    }

    /**
     * PFCOUNT key [key ...]
     * Available since 2.8.9.
     * Time complexity: O(1) with every small average constant times when called with a single key.
     * O(N) with N being the number of keys, and much bigger constant times, when called with multiple keys.
     *
     * @param string|string[] $keys
     * @return int
     */
    public function pfcount($keys) {
        return $this->returnCommand(
            new Command('PFCOUNT', Parameter::keys($keys))
        );
    }

    /**
     * PFMERGE destkey sourcekey [sourcekey ...]
     * Available since 2.8.9.
     * Time complexity: O(N) to merge N HyperLogLogs, but with high constant times.
     *
     * @param string $destkey
     * @param string|string[] $sourcekeys
     * @return bool The command just returns True.
     */
    public function pfmerge($destkey, $sourcekeys) {
        return $this->returnCommand(
            new Command('PFMERGE', [
                Parameter::key($destkey),
                Parameter::keys($sourcekeys),
            ])
        );
    }

}
