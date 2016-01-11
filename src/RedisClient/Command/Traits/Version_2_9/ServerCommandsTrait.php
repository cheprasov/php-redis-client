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
namespace RedisClient\Command\Traits\Version_2_9;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version_2_8\ServerCommandsTrait as ServerCommandsTraitVersion28;
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion28;

    /**
     * CLIENT PAUSE timeout
     * Available since 2.9.50.
     * Time complexity: O(1)
     *
     * @param int $timeout
     * @return true The command returns True or an error if the timeout is invalid.
     */
    public function clientPause($timeout) {
        return $this->returnCommand(['CLIENT', 'PAUSE'], [Parameter::integer($timeout)]);
    }

}
