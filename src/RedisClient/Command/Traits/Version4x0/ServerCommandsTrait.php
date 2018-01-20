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

use RedisClient\Command\Traits\Version3x2\ServerCommandsTrait as ServerCommandsTraitVersion3x2;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion3x2;

    /**
     * FLUSHALL [ASYNC]
     * Available since 1.0.0.
     * @link http://redis.io/commands/flushall
     *
     * @param bool $async
     * @return bool
     */
    public function flushall($async = false) {
        return $this->returnCommand(['FLUSHALL'], null, $async ? ['ASYNC'] : null);
    }

    /**
     * FLUSHDB [ASYNC]
     * Available since 1.0.0.
     * @link http://redis.io/commands/flushdb
     *
     * @param bool $async
     * @return bool
     */
    public function flushdb($async = false) {
        return $this->returnCommand(['FLUSHDB'], null, $async ? ['ASYNC'] : null);
    }

}
