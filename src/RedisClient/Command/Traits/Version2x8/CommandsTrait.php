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
namespace RedisClient\Command\Traits\Version2x8;

use RedisClient\Command\Traits\AbstractCommandsTrait;
use RedisClient\Command\Traits\Version2x6\ListsCommandsTrait;
use RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait;
use RedisClient\Command\Traits\Version2x6\TransactionsCommandsTrait;

trait CommandsTrait {

    use AbstractCommandsTrait;

    use ConnectionCommandsTrait;
    use HashesCommandsTrait;
    use HyperLogLogCommandsTrait;
    use KeysCommandsTrait;
    use LatencyCommandsTrait;
    use ListsCommandsTrait;
    use PubSubCommandsTrait;
    use ScriptingCommandsTrait;
    use ServerCommandsTrait;
    use SetsCommandsTrait;
    use SortedSetsCommandsTrait;
    use StringsCommandsTrait;
    use TransactionsCommandsTrait;

    /**
     * @return string
     */
    public function getSupportedVersion() {
        return '2.8';
    }

}
