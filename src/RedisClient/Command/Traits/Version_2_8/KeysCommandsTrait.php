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
use RedisClient\Command\Traits\Version_2_6\KeysCommandsTrait as KeysCommandsTraitVersion26;

/**
 * trait KeysCommandsTrait
 */
trait KeysCommandsTrait {

    use KeysCommandsTraitVersion26;

    /**
     * SCAN cursor [MATCH pattern] [COUNT count]
     * Available since 2.8.0.
     * Time complexity: O(1) for every call. O(N) for a complete iteration.
     * @link http://redis.io/commands/scan
     *
     * @param int $cursor
     * @param string|null $pattern
     * @param int|null $count
     * @return mixed
     */
    public function scan($cursor, $pattern = null, $count = null) {
        $params = [Parameter::integer($cursor)];
        if ($pattern) {
            $params[] = Parameter::string('MATCH');
            $params[] = Parameter::string($pattern);
        }
        if ($count) {
            $params[] = Parameter::string('COUNT');
            $params[] = Parameter::integer($count);
        }
        return $this->returnCommand(['SCAN'], $params);
    }

}
