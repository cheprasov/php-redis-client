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

use RedisClient\Command\Traits\Version2x8\ConnectionCommandsTrait as ConnectionCommandsTraitVersion2x8;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ConnectionCommandsTrait {

    use ConnectionCommandsTraitVersion2x8;

    /**
     * SWAPDB index index
     * Available since 4.0.0.
     * @link http://redis.io/commands/swapdb
     *
     * @param int $db1
     * @param int $db2
     * @return bool
     */
    public function swapdb($db1, $db2) {
        return $this->returnCommand(['SWAPDB'], null, [$db1, $db2]);
    }

}
