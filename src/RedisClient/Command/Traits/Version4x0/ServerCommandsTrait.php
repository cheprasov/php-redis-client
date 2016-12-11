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
namespace RedisClient\Command\Traits\Version4x0;

use RedisClient\Command\Traits\Version3x2\ServerCommandsTrait as ServerCommandsTraitVersion3x2;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion3x2;

}
