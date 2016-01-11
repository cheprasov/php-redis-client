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
namespace RedisClient\Command\Traits\Version_2_8;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version_2_6\StringsCommandsTrait as StringsCommandsTraitVersion26;

trait StringsCommandsTrait {

    use StringsCommandsTraitVersion26;

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
        $params = [
            Parameter::key($key),
            Parameter::string($bit),
        ];
        if (isset($start)) {
            $params[] = Parameter::integer($start);
            if (isset($end)) {
                $params[] = Parameter::integer($end);
            }
        }
        return $this->returnCommand(['BITPOS'], $params);
    }

}
