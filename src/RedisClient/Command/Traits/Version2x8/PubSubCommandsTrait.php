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
namespace RedisClient\Command\Traits\Version2x8;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version2x6\PubSubCommandsTrait as PubSubCommandsTraitVersion26;

/**
 * PubSub
 * @link http://redis.io/commands#pubsub
 */
trait PubSubCommandsTrait {

    use PubSubCommandsTraitVersion26;

    /**
     * PUBSUB subcommand [argument [argument ...]]
     * Available since 2.8.0.
     * Time complexity: O(N) for the CHANNELS subcommand, where N is the number of active channels,
     * and assuming constant time pattern matching (relatively short channels and patterns).
     * O(N) for the NUMSUB subcommand, where N is the number of requested channels.
     * O(1) for the NUMPAT subcommand.
     *
     * @param string $subcommand
     * @param string|string[] $arguments
     * @return array|int
     */
    public function pubsub($subcommand, $arguments = null) {
        $params = [
            Parameter::enum($subcommand, ['CHANNELS', 'NUMSUB', 'NUMPAT'])
        ];
        if (isset($arguments)) {
            $params[] = Parameter::strings($arguments);
        }
        return $this->returnCommand(['PUBSUB'], $params);
    }

}
