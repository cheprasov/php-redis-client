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

use RedisClient\Command\Traits\Version2x6\HashesCommandsTrait as HashesCommandsTraitVersion2x6;

/**
 * Hashes Commands
 * @link http://redis.io/commands#hash
 */
trait HashesCommandsTrait {

    use HashesCommandsTraitVersion2x6;

    /**
     * HSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call.
     * @link http://redis.io/commands/hscan
     *
     * @param string $key
     * @param int $cursor
     * @param null|string $pattern
     * @param null|int $count
     * @return mixed
     */
    public function hscan($key, $cursor, $pattern = null, $count = null) {
        $params = [$key, $cursor];
        if (isset($pattern)) {
            $params[] = 'MATCH';
            $params[] = $pattern;
        }
        if (isset($count)) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        return $this->returnCommand(['HSCAN'], $key, $params);
    }


}
