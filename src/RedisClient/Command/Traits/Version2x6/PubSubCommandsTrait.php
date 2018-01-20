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
namespace RedisClient\Command\Traits\Version2x6;

use RedisClient\Exception\InvalidArgumentException;

/**
 * PubSub Commands
 * @link http://redis.io/commands#pubsub
 */
trait PubSubCommandsTrait {

    /**
     * PSUBSCRIBE pattern [pattern ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of patterns the client is already subscribed to.
     * @link http://redis.io/commands/psubscribe
     *
     * @param string|string[] $patterns
     * @param \Closure|string|array $callback
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function psubscribe($patterns, $callback) {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Function $callback is not callable');
        }
        return $this->subscribeCommand(['PSUBSCRIBE'], ['PUNSUBSCRIBE'], (array)$patterns, $callback);
    }

    /**
     * PUBLISH channel message
     * Available since 2.0.0.
     * Time complexity: O(N+M) where N is the number of clients subscribed to the receiving channel
     * and M is the total number of subscribed patterns (by any client).
     *
     * @param string $channel
     * @param string $message
     * @return int The number of clients that received the message.
     */
    public function publish($channel, $message) {
        return $this->returnCommand(['PUBLISH'], null, [$channel, $message]);
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
        return $this->returnCommand(['PUNSUBSCRIBE'], null, isset($patterns) ? (array)$patterns : null);
    }

    /**
     * SUBSCRIBE channel [channel ...]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of channels to subscribe to.
     *
     * @link http://redis.io/commands/psubscribe
     *
     * @param string|string[] $channels
     * @param \Closure|string|array $callback
     * @return string[]
     * @throws InvalidArgumentException
     */
    public function subscribe($channels, $callback) {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Function $callback is not callable');
        }
        return $this->subscribeCommand(['SUBSCRIBE'], ['UNSUBSCRIBE'], (array)$channels, $callback);
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
        return $this->returnCommand(['UNSUBSCRIBE'], null, isset($channels) ? (array)$channels : null);
    }

}
