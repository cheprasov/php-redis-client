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

use RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait as ScriptingCommandsTraitVersion2x6;

/**
 * Scripting Commands
 * @link http://redis.io/commands#scripting
 */
trait ScriptingCommandsTrait {

    use ScriptingCommandsTraitVersion2x6;

    /**
     * SCRIPT DEBUG YES|SYNC|NO
     * Available since 3.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/script-debug
     *
     * @param string $param YES|SYNC|NO
     * @return
     */
    public function scriptDebug($param) {
        return $this->returnCommand(['SCRIPT', 'DEBUG'], null, [$param]);
    }

}
