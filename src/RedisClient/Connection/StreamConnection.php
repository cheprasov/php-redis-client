<?php

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

    public function __construct($server, $timeout = null) {
        $this->server = $server;
        if (is_numeric($timeout)) {
            $this->timeout = ceil($timeout * 1000000);
        }
    }

    public function __destruct() {
        if ($this->resource) {
            fclose($this->resource);
        }
    }

    protected function getResource() {
        if (!$this->resource) {
            if (!$this->resource = stream_socket_client($this->server)) {
                throw new ConnectionException(sprintf(
                    'Unable to connect to %s', $this->server
                ));
            }
            if ($this->timeout) {
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
