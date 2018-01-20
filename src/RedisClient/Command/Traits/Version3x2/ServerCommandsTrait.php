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

use RedisClient\Command\Traits\Version2x9\ServerCommandsTrait as ServerCommandsTraitVersion2x9;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion2x9;

    /**
     * CLIENT REPLY ON|OFF|SKIP
     * Available since 3.2.
     * Time complexity: O(1)
     * @link http://redis.io/commands/client-reply
     *
     * @param string $param
     * @return bool|null
     */
    public function clientReply($param) {
        return $this->returnCommand(['CLIENT', 'REPLY'], null, [$param]);
    }

    /**
     * DEBUG HELP
     * Available since 3.2.
     *
     * @return string[]
     */
    public function debugHelp() {
        return $this->returnCommand(['DEBUG', 'HELP']);
    }

}
