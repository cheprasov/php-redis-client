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

use RedisClient\Command\Traits\Version2x6\KeysCommandsTrait as KeysCommandsTraitVersion2x6;

/**
 * Keys Commands
 * @link http://redis.io/commands#generic
 */
trait KeysCommandsTrait {

    use KeysCommandsTraitVersion2x6;

    /**
     * SCAN cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration.
     * @link http://redis.io/commands/scan
     *
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function scan($cursor, $pattern = null, $count = null) {
        $params = [$cursor];
        if ($pattern) {
            $params[] = 'MATCH';
            $params[] = $pattern;
        }
        if ($count) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        return $this->returnCommand(['SCAN'], null, $params);
    }

}
