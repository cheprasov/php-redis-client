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

use RedisClient\Command\Traits\Version2x8\SetsCommandsTrait as SetsCommandsTraitVersion2x8;

/**
 * Sets Commands
 * @link http://redis.io/commands#set
 */
trait SetsCommandsTrait {

    use SetsCommandsTraitVersion2x8;

    /**
     * SPOP key [count]
     * Available since 1.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/spop
     *
     * @param string $key
     * @param int|null $count Redis >= 3.2
     * @return string|string[]|null The removed element, or null when key does not exist.
     */
    public function spop($key, $count = null) {
        $params = [$key];
        if ($count) {
            $params[] = $count;
        }
        return $this->returnCommand(['SPOP'], $key, $params);
    }

}
