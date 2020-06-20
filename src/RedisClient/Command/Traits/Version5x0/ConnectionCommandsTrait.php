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
namespace RedisClient\Command\Traits\Version5x0;

use RedisClient\Command\Traits\Version4x0\ConnectionCommandsTrait as ConnectionCommandsTraitVersion4x0;

/**
 * Connection Commands
 * @link http://redis.io/commands#connection
 */
trait ConnectionCommandsTrait {

    use ConnectionCommandsTraitVersion4x0;

    /**
     * CLIENT ID
     * @link http://redis.io/commands/client-id
     *
     * @return int
     */
    public function clientId() {
        return $this->returnCommand(['CLIENT', 'ID'], null, null);
    }

    /**
     * CLIENT UNBLOCK client-id [TIMEOUT|ERROR]
     * @link https://redis.io/commands/client-unblock
     *
     * @param int $clientId
     * @param string|null $timeoutOrError
     * @return int
     */
    public function clientUnblock($clientId, $timeoutOrError = null) {
        $params = [$clientId];
        if ($timeoutOrError) {
            $params[] = $timeoutOrError;
        }
        return $this->returnCommand(['CLIENT', 'UNBLOCK'], null, $params);
    }

}
