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
namespace RedisClient\Command\Traits\Version6x0;

use RedisClient\Command\Traits\Version2x6\ListsCommandsTrait as ListsCommandsTrait2x6;

/**
 * List Commands
 * @link http://redis.io/commands#list
 */
trait ListsCommandsTrait {

    use ListsCommandsTrait2x6;

    /**
     * LPOS key element [FIRST rank] [COUNT num-matches] [MAXLEN len]
     * @link https://redis.io/commands/lpos
     *
     * @param string $key
     * @param string $element
     * @param int|null $rank
     * @param int|null $numMatches
     * @param int|null $len
     * @return int|int[]|null
     */
    public function lpos($key, $element, $rank = null, $numMatches = null, $len = null) {
        $params = [$key, $element];
        if (isset($rank)) {
            $params[] = 'FIRST';
            $params[] = $rank;
        }
        if (isset($numMatches)) {
            $params[] = 'COUNT';
            $params[] = $rank;
        }
        if (isset($len)) {
            $params[] = 'MAXLEN';
            $params[] = $rank;
        }
        return $this->returnCommand(['LPOS'], $key, $params);
    }

}
