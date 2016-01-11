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
use RedisClient\Command\Traits\Version_2_6\SetsCommandsTrait as SetsCommandsTraitVersion26;

trait SetsCommandsTrait {

    use SetsCommandsTraitVersion26;

    /**
     * SSCAN key cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration,
     * including enough command calls for the cursor to return back to 0.
     * N is the number of elements inside the collection.
     * @link http://redis.io/commands/sscan
     *
     * @param string $key
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function sscan($key, $cursor, $pattern = null, $count = null) {
        $params = [
            Parameter::key($key),
            Parameter::integer($cursor),
        ];
        if ($pattern) {
            $params[] = Parameter::string('MATCH');
            $params[] = Parameter::string($pattern);
        }
        if ($count) {
            $params[] = Parameter::string('COUNT');
            $params[] = Parameter::integer($count);
        }
        return $this->returnCommand(['SSCAN'], $params);
    }

}
