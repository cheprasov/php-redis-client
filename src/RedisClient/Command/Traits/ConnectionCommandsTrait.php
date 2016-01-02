<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

/**
 * Connection
 * @link http://redis.io/commands#connection
 */
trait ConnectionCommandsTrait {

    /**
     * AUTH password
     * Available since 1.0.0.
     *
     * @param string $password
     * @return bool True
     */
    public function auth($password) {
        return $this->returnCommand(
            new Command('AUTH', Parameter::string($password))
        );
    }

    /**
     * ECHO message
     * Available since 1.0.0.
     *
     * @param string $message
     * @return string Returns message
     */
    public function echoMessage($message) {
        return $this->returnCommand(
            new Command('ECHO', Parameter::string($message))
        );
    }

    /**
     * PING [message]
     * Available since 1.0.0.
     *
     * @param string $message
     * @return string Returns message
     */
    public function ping($message = null) {
        return $this->returnCommand(
            new Command('PING', isset($message) ? Parameter::string($message) : null)
        );
    }

    /**
     * QUIT
     * Available since 1.0.0.
     *
     * @return bool Always True
     */
    public function quit() {
        return $this->returnCommand(
            new Command('QUIT')
        );
    }

    /**
     * SELECT index
     * Available since 1.0.0.
     *
     * @param int $db
     * @return bool
     */
    public function select($db) {
        return $this->returnCommand(
            new Command('SELECT', Parameter::integer($db))
        );
    }

}
