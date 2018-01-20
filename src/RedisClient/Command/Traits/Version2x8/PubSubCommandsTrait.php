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

use RedisClient\Command\Traits\Version2x6\PubSubCommandsTrait as PubSubCommandsTraitVersion2x6;

/**
 * PubSub Commands
 * @link http://redis.io/commands#pubsub
 */
trait PubSubCommandsTrait {

    use PubSubCommandsTraitVersion2x6;

    /**
     * PUBSUB subcommand [argument [argument ...]]
     * Available since 2.8.0.
     * Time complexity: O(N) for the CHANNELS subcommand, where N is the number of active channels,
     * and assuming constant time pattern matching (relatively short channels and patterns).
     * O(N) for the NUMSUB subcommand, where N is the number of requested channels.
     * O(1) for the NUMPAT subcommand.
     *
     * @param string $subcommand CHANNELS|NUMSUB|NUMPAT
     * @param string|string[] $arguments
     * @return array|int
     */
    public function pubsub($subcommand, $arguments = null) {
        $params = [$subcommand];
        if (isset($arguments)) {
            $params[] = (array) $arguments;
        }
        return $this->returnCommand(['PUBSUB'], null, $params);
    }

}
