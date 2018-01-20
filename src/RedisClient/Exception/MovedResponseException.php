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
namespace RedisClient\Exception;

class MovedResponseException extends ErrorResponseException {

    /**
     * @var int
     */
    protected $slot = 0;

    /**
     * @var string
     */
    protected $server = '';

    /**
     * @param string $message
     */
    public function __construct($message) {
        // MOVED slot server
        list(, $slot, $server) = explode(' ', $message);
        $this->server = $server;
        $this->slot = (int)$slot;
    }

    /**
     * @return int
     */
    public function getSlot() {
        return $this->slot;
    }

    /**
     * @return string
     */
    public function getServer() {
        return $this->server;
    }
}
