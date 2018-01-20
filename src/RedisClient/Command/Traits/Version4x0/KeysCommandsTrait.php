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

use RedisClient\Command\Traits\Version3x2\KeysCommandsTrait as KeysCommandsTraitVersion3x2;

/**
 * Keys Commands
 * @link http://redis.io/commands#generic
 */
trait KeysCommandsTrait {

    use KeysCommandsTraitVersion3x2;

    /**
     * UNLINK key [key ...]
     * Available since 4.0.0.
     * Time complexity: O(1) for each key removed regardless of its size.
     * @link https://redis.io/commands/unlink
     *
     * @param string|string[] $keys
     * @return int The number of keys that were unlinked.
     */
    public function unlink($keys) {
        $keys = (array)$keys;
        return $this->returnCommand(['UNLINK'], $keys, $keys);
    }

}
