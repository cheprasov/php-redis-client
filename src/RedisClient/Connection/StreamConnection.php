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
     * @param string $server
     * @param int|float|null $timeout
     */
    public function __construct($server, $timeout = null) {
        $this->server = $server;
        if (is_numeric($timeout)) {
            $this->timeout = ceil($timeout * 1000000);
        }
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
     * @return resource
     * @throws ConnectionException
     */
    protected function getResource() {
        if (!$this->resource) {
            if (!$this->resource = stream_socket_client($this->server)) {
                throw new ConnectionException(sprintf(
                    'Unable to connect to %s', $this->server
                ));
            }
            if (isset($this->timeout)) {
                stream_set_timeout($this->resource, 0, $this->timeout);
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
     * @return string
     */
    public function read($length) {
        return fread($this->getResource(), $length);
    }

}
