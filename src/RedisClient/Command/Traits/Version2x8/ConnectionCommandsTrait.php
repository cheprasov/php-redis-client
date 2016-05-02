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
namespace RedisClient\Command\Traits\Version2x8;

use RedisClient\Command\Traits\Version2x6\ConnectionCommandsTrait as ConnectionCommandsTraitVersion2x6;

/**
 * Connection Commands
 * @link http://redis.io/commands#connection
 *
 * @method string echo($message)
 */
trait ConnectionCommandsTrait {

    use ConnectionCommandsTraitVersion2x6;

    /**
     * PING [message]
     * Available since 1.0.0.
     * @link http://redis.io/commands/ping
     *
     * @param string $message
     * @return string Returns message
     */
    public function ping($message = null) {
        return $this->returnCommand(['PING'], isset($message) ? [$message] : null);
    }

}
