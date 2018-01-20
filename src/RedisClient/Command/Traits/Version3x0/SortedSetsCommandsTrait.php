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
namespace RedisClient\Command\Traits\Version3x0;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version2x8\SortedSetsCommandsTrait as SortedSetsCommandsTrait28;

/**
 * SortedSets Commands
 * @link http://redis.io/commands#set
 * @link http://redis.io/topics/data-types#sorted-sets
 */
trait SortedSetsCommandsTrait {

    use SortedSetsCommandsTrait28;

    /**
     * ZADD key [NX|XX] [CH] [INCR] score member [score member ...]
     * Available since 1.2.0.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/zadd
     *
     * @param string $key
     * @param array $members array(member => score [, member => score ...])
     * @param string|null $nx NX or XX
     * @param bool|false $ch
     * @param bool|false $incr
     * @return int|string
     */
    public function zadd($key, array $members, $nx = null, $ch = false, $incr = false) {
        $params = [$key];
        if ($nx) {
            $params[] = $nx;
        }
        if ($ch) {
            $params[] = 'CH';
        }
        if ($incr) {
            $params[] = 'INCR';
        }
        $params[] = Parameter::assocArrayFlip($members);
        return $this->returnCommand(['ZADD'], $key, $params);
    }

}
