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
namespace RedisClient\Command\Traits\Version5x0;

use RedisClient\Command\Traits\Version3x0\SortedSetsCommandsTrait as SortedSetsCommandsTrait3x0;

/**
 * SortedSets Commands
 * @link http://redis.io/commands#set
 * @link http://redis.io/topics/data-types#sorted-sets
 */
trait SortedSetsCommandsTrait {

    use SortedSetsCommandsTrait3x0;

    /**
     * BZPOPMAX key [key ...] timeout
     * Available since 5.0.0.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     * @link https://redis.io/commands/bzpopmax
     *
     * @param string|string[] $keys
     * @param int $timeout
     * @return array
     */
    public function bzpopmax($keys, $timeout = 0) {
        $params = (array)$keys;
        $params[] = $timeout;
        return $this->returnCommand(['BZPOPMAX'], $keys, $params);
    }

    /**
     * BZPOPMIN key [key ...] timeout
     * Available since 5.0.0.
     * Time complexity: O(log(N)) with N being the number of elements in the sorted set.
     * @link https://redis.io/commands/bzpopmin
     *
     * @param string|string[] $keys
     * @param int $timeout
     * @return array
     */
    public function bzpopmin($keys, $timeout = 0) {
        $params = (array)$keys;
        $params[] = $timeout;
        return $this->returnCommand(['BZPOPMIN'], $keys, $params);
    }

    /**
     * ZPOPMAX key [count]
     * Available since 5.0.0.
     * Time complexity: O(log(N)*M) with N being the number of elements in the sorted set,
     * and M being the number of elements popped.
     * @link https://redis.io/commands/zpopmax
     *
     * @param string $key
     * @param string $count
     * @return array
     */
    public function zpopmax($key, $count = null) {
        $params = [$key];
        if (isset($count)) {
            $params[] = $count;
        }
        return $this->returnCommand(['ZPOPMAX'], [$key], $params);
    }

    /**
     * ZPOPMIN key [count]
     * Available since 5.0.0.
     * Time complexity: O(log(N)*M) with N being the number of elements in the sorted set,
     * and M being the number of elements popped.
     * @link https://redis.io/commands/zpopmin
     *
     * @param string $key
     * @param string $count
     * @param int $timeout
     * @return array
     */
    public function zpopmin($key, $count = null) {
        $params = [$key];
        if (isset($count)) {
            $params[] = $count;
        }
        return $this->returnCommand(['ZPOPMIN'], [$key], $params);
    }

}
