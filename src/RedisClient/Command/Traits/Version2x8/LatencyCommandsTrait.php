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

/**
 * Latency Monitoring
 * @link http://redis.io/topics/latency-monitor
 */
trait LatencyCommandsTrait {

    /**
     * LATENCY LATEST
     * Available since 2.8.13
     * @link http://redis.io/topics/latency-monitor
     *
     * @return array
     */
    public function latencyLatest() {
        return $this->returnCommand(['LATENCY', 'LATEST']);
    }

    /**
     * LATENCY HISTORY event-name
     * Available since 2.8.13
     * @link http://redis.io/topics/latency-monitor
     *
     * @param string $eventName
     * @return array
     */
    public function latencyHistory($eventName) {
        return $this->returnCommand(['LATENCY', 'HISTORY'], [Parameter::string($eventName)]);
    }

    /**
     * LATENCY RESET [event-name ... event-name]
     * Available since 2.8.13
     * @link http://redis.io/topics/latency-monitor
     *
     * @param string|string[] $eventNames
     * @return int
     */
    public function latencyReset($eventNames = null) {
        return $this->returnCommand(['LATENCY', 'RESET'], Parameter::strings($eventNames));
    }

    /**
     * LATENCY GRAPH event-name
     * Available since 2.8.13
     * @link http://redis.io/topics/latency-monitor
     *
     * @param string $eventName
     * @return string
     */
    public function latencyGraph($eventName) {
        return $this->returnCommand(['LATENCY', 'GRAPH'], [Parameter::string($eventName)]);
    }

    /**
     * LATENCY DOCTOR
     * Available since 2.8.13
     * @link http://redis.io/topics/latency-monitor
     *
     * @return string
     */
    public function latencyDoctor() {
        return $this->returnCommand(['LATENCY', 'DOCTOR']);
    }

}
