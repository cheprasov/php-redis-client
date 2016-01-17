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
namespace RedisClient\Command\Traits\Version2x6;

/**
 * Connection
 * @link http://redis.io/commands#connection
 */
trait ConnectionCommandsTrait {

    /**
     * AUTH password
     * Available since 1.0.0.
     * @link http://redis.io/commands/auth
     *
     * @param string $password
     * @return bool True
     */
    public function auth($password) {
        return $this->returnCommand(['AUTH'], [$password]);
    }

    /**
     * ECHO message
     * Available since 1.0.0.
     * @link http://redis.io/commands/echo
     *
     * @param string $message
     * @return string Returns message
     */
    public function echoMessage($message) {
        return $this->returnCommand(['ECHO'], [$message]);
    }

    /**
     * PING [message]
     * Available since 1.0.0.
     * @link http://redis.io/commands/ping
     *
     * @param string $message
     * @return string Returns message
     */
    public function ping($message = null) {
        return $this->returnCommand(['PING'], isset($message) ? [$message] : null);
    }

    /**
     * QUIT
     * Available since 1.0.0.
     * @link http://redis.io/commands/quit
     *
     * @return bool Always True
     */
    public function quit() {
        return $this->returnCommand(['QUIT']);
    }

    /**
     * SELECT index
     * Available since 1.0.0.
     * @link http://redis.io/commands/select
     *
     * @param int $db
     * @return bool
     */
    public function select($db) {
        return $this->returnCommand(['SELECT'], [$db]);
    }

}
