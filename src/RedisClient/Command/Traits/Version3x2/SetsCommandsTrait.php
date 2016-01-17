<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <cheprasov.84@ya.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RedisClient\Command\Traits\Version3x2;

use RedisClient\Command\Traits\Version2x8\SetsCommandsTrait as SetsCommandsTraitVersion28;

trait SetsCommandsTrait {

    use SetsCommandsTraitVersion28;

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
        return $this->returnCommand(['SPOP'], $params);
    }

}
