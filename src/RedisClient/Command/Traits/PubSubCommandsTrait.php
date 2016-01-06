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

use RedisClient\Command\Parameter\Parameter;

/**
 * PubSub
 * @link http://redis.io/commands#pubsub
 */
trait PubSubCommandsTrait {

    /**
     * PSUBSCRIBE pattern [pattern ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of patterns the client is already subscribed to.
     */
    public function psubscribe($patterns) {
        return $this->returnCommand(['PSUBSCRIBE'], Parameter::strings($patterns));
    }

    /**
     * PUBLISH channel message
     * Available since 2.0.0.
     * Time complexity: O(N+M) where N is the number of clients subscribed to the receiving channel
     * and M is the total number of subscribed patterns (by any client).
     *
     * @param string $channel
     * @param string $message
     * @return int The number of clients that received the message.e
     */
    public function publish($channel, $message) {
        return $this->returnCommand(['PUBLISH'], [
            Parameter::string($channel),
            Parameter::string($message)
        ]);
    }

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

    /**
     * PUNSUBSCRIBE [pattern [pattern ...]]
     * Available since 2.0.0.
     * Time complexity: O(N+M) where N is the number of patterns the client is already subscribed
     * and M is the number of total patterns subscribed in the system (by any client).
     *
     * @param string|string[]|null $patterns
     * @return
     */
    public function punsubscribe($patterns = null) {
        return $this->returnCommand(['PUNSUBSCRIBE'], isset($patterns) ? Parameter::strings($patterns) : null);
    }

    /**
     * SUBSCRIBE channel [channel ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of channels to subscribe to.
     *
     * @param string|string[] $channels
     * @return
     */
    public function subscribe($channels) {
        return $this->returnCommand(['SUBSCRIBE'], Parameter::strings($channels));
    }

    /**
     * UNSUBSCRIBE [channel [channel ...]]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of clients already subscribed to a channel.
     *
     * @param string|string[]|null $channels
     * @return
     */
    public function unsubscribe($channels) {
        return $this->returnCommand(['UNSUBSCRIBE'], isset($channels) ? Parameter::strings($channels) : null);
    }

}
