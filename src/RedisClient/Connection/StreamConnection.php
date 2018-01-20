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
namespace RedisClient\Connection;

use RedisClient\Exception\ConnectionException;

class StreamConnection implements ConnectionInterface {

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var int|null
     */
    protected $timeout;

    /**
     * @var callable
     */
    protected $onConnectCallback;

    /**
     * @param string $server
     * @param int|float|null $timeout
     */
    public function __construct($server, $timeout = null) {
        $this->setServer($server);
        $this->setTimeout($timeout);
    }

    /**
     * @param string $server
     */
    protected function setServer($server) {
        if (0 !== strpos($server, 'tcp://') && 0 !== strpos($server, 'unix://')) {
            $this->server = 'tcp://' . $server;
        } else {
            $this->server = $server;
        }
    }

    /**
     * @param null|int $timeout
     */
    protected function setTimeout($timeout = null) {
        $this->timeout = $timeout ? ceil($timeout * 1000000) : null;
    }

    /**
     *
     */
    public function __destruct() {
        if ($this->resource) {
            fclose($this->resource);
        }
    }

    /**
     * @return string
     */
    public function getServer() {
        return $this->server;
    }

    /**
     * @param callable $callback
     */
    public function onConnect($callback) {
        $this->onConnectCallback = $callback;
    }

    /**
     * @return resource
     * @throws ConnectionException
     */
    protected function getResource() {
        if (!$this->resource) {
            $errno = null;
            $errstr = null;
            if (!$this->resource = stream_socket_client($this->server, $errno, $errstr)) {
                throw new ConnectionException('Unable to connect to '. $this->server . ' ('. $errstr .')');
            }
            if (isset($this->timeout)) {
                stream_set_timeout($this->resource, 0, $this->timeout);
            }
            if ($this->onConnectCallback && is_callable($this->onConnectCallback)) {
                call_user_func($this->onConnectCallback);
            }
        }
        return $this->resource;
    }

    /**
     * @param string $string
     * @return int
     */
    public function write($string) {
        return fwrite($this->getResource(), $string, strlen($string));
    }

    /**
     * @return string
     */
    public function readLine() {
        return fgets($this->getResource());
    }

    /**
     * @param int $length
     * @return string|null
     */
    public function read($length) {
        $resource = $this->getResource();
        $left = $length;
        $data = '';
        do {
            $read = fread($resource, min($left, 8192));
            if (false === $read) {
                return null;
            }
            $data .= $read;
            $left = $length - strlen($data);
        } while ($left > 0);

        return $data;
    }

}
