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

use RedisClient\Command\Traits\Version2x6\SetsCommandsTrait as SetsCommandsTraitVersion2x6;

/**
 * Sets Commands
 * @link http://redis.io/commands#set
 */
trait SetsCommandsTrait {

    use SetsCommandsTraitVersion2x6;

    /**
     * SSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration,
     * including enough command calls for the cursor to return back to 0.
     * N is the number of elements inside the collection.
     * @link http://redis.io/commands/sscan
     *
     * @param string $key
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function sscan($key, $cursor, $pattern = null, $count = null) {
        $params = [$key, $cursor];
        if ($pattern) {
            $params[] = 'MATCH';
            $params[] = $pattern;
        }
        if ($count) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        return $this->returnCommand(['SSCAN'], $key, $params);
    }

}
