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
namespace RedisClient\Command\Traits;

trait AllCommandsTrait {
    use ClusterCommandsTrait;
    use ConnectionCommandsTrait;
    use HashesCommandsTrait;
    use HyperLogLogCommandsTrait;
    use KeysCommandsTrait;
    use ListsCommandsTrait;
    use ScriptingCommandsTrait;
    use ServerCommandsTrait;
    use SetsCommandsTrait;
    use SortedSetsCommandsTrait;
    use StringsCommandsTrait;
    use TransactionsCommandsTrait;

    /**
     * @param array $command
     * @param array|null $params
     * @param int|null $parserId
     */
    abstract protected function returnCommand(array $command, array $params = null, $parserId = null);
}
