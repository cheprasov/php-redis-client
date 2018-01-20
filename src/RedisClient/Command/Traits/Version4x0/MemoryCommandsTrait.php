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
namespace RedisClient\Command\Traits\Version4x0;

/**
 * @link http://antirez.com/news/110
 */
trait MemoryCommandsTrait {

    /**
     * MEMORY DOCTOR
     * Available since 4.0.0
     *
     * @return string
     */
    public function memoryDoctor() {
        return $this->returnCommand(['MEMORY', 'DOCTOR']);
    }

    /**
     * MEMORY HELP
     * Available since 4.0.0
     *
     * @return string[]
     */
    public function memoryHelp() {
        return $this->returnCommand(['MEMORY', 'HELP']);
    }

    /**
     * MEMORY USAGE <key> [SAMPLES <count>]
     * Estimate memory usage of key
     * Available since 4.0.0
     *
     * @param string $key
     * @param null|int $count
     * @return int|null
     */
    public function memoryUsage($key, $count = null) {
        return $this->returnCommand(['MEMORY', 'USAGE'], [$key], isset($count) ? [$key, 'SAMPLES', $count] : [$key]);
    }

    /**
     * MEMORY STATS
     * Show memory usage details
     * Available since 4.0.0
     *
     * @return array
     */
    public function memoryStats() {
        return $this->returnCommand(['MEMORY', 'STATS']);
    }

    /**
     * MEMORY PURGE
     * Ask the allocator to release memory
     * Available since 4.0.0
     *
     * @return bool
     */
    public function memoryPurge() {
        return $this->returnCommand(['MEMORY', 'PURGE']);
    }

    /**
     * MEMORY MALLOC-STATS
     * Show allocator internal stats
     * Available since 4.0.0
     *
     * @return string
     */
    public function memoryMallocStats() {
        return $this->returnCommand(['MEMORY', 'MALLOC-STATS']);
    }
}
