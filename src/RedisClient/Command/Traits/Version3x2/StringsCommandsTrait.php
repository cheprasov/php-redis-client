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
namespace RedisClient\Command\Traits\Version3x2;

use RedisClient\Command\Traits\Version2x8\StringsCommandsTrait as StringsCommandsTraitVersion2x8;

/**
 * Strings Commands
 * @link http://redis.io/commands#string
 */
trait StringsCommandsTrait {

    use StringsCommandsTraitVersion2x8;

    /**
     * BITFIELD key [GET type offset] [SET type offset value] [INCRBY type offset increment] [OVERFLOW WRAP|SAT|FAIL]
     * Available since 3.2.0.
     * Time complexity: O(1) for each subcommand specified
     *
     * @param string $key
     * @param array $subcommands Example: [['GET', 'type', 'offset'], ['OVERFLOW', 'WRAP'], ['SET', 'type', 'offset', 'value']]
     * @return mixed
     */
    public function bitfield($key, array $subcommands) {
        $params = [$key];
        // I know, I know... but it is faster!
        if (is_array($subcommands[0])) {
            foreach ($subcommands as $subcommand) {
                foreach ($subcommand as $p) {
                    $params[] = $p;
                }
            }
        } else {
            foreach ($subcommands as $p) {
                $params[] = $p;
            }
        }
        return $this->returnCommand(['BITFIELD'], $key, $params);
    }

}
