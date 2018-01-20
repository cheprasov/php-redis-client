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

use RedisClient\Command\Traits\Version2x6\StringsCommandsTrait as StringsCommandsTraitVersion2x6;

/**
 * Strings Commands
 * @link http://redis.io/commands#string
 */
trait StringsCommandsTrait {

    use StringsCommandsTraitVersion2x6;

    /**
     * BITPOS key bit [start] [end]
     * Available since 2.8.7.
     * Time complexity: O(N)
     * @link http://redis.io/commands/bitpos
     *
     * @param string $key
     * @param string $bit
     * @param null|int $start
     * @param null|int $end
     * @return int The command returns the position of the first bit set to 1 or 0 according to the request.
     */
    public function bitpos($key, $bit, $start = null, $end = null) {
        $params = [$key, $bit];
        if (isset($start)) {
            $params[] = $start;
            if (isset($end)) {
                $params[] = $end;
            }
        }
        return $this->returnCommand(['BITPOS'], $key, $params);
    }

}
