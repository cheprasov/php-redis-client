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
namespace RedisClient\Command\Traits\Version2x9;

use RedisClient\Command\Traits\Version2x8\ServerCommandsTrait as ServerCommandsTraitVersion2x8;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion2x8;

    /**
     * CLIENT PAUSE timeout
     * Available since 2.9.50.
     * Time complexity: O(1)
     * @link http://redis.io/commands/client-pause
     *
     * @param int $timeout
     * @return bool The command returns True or an error if the timeout is invalid.
     */
    public function clientPause($timeout) {
        return $this->returnCommand(['CLIENT', 'PAUSE'], null, [$timeout]);
    }

}
