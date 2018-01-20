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
namespace RedisClient\Command\Traits\Version3x2;

use RedisClient\Command\Traits\Version2x8\HashesCommandsTrait as HashesCommandsTraitVersion2x8;

/**
 * Hashes Commands
 * @link http://redis.io/commands#hash
 */
trait HashesCommandsTrait {

    use HashesCommandsTraitVersion2x8;

    /**
     * HSTRLEN key field
     * Available since 3.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/hstrlen
     *
     * @param string $key
     * @param string $field
     * @return int the string length of the value associated with field,
     * or 0 when field is not present in the hash or key does not exist at all.
     */
    public function hstrlen($key, $field) {
        return $this->returnCommand(['HSTRLEN'], $key, [$key, $field]);
    }

}
