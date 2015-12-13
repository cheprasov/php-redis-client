<?php

namespace RedisClient\Connection;

class StreamConnection implements ConnectionInterface {

    const READ_LENGTH = 4096;

    const TIMEOUT = 10;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $address;

    public function __construct($address = 'tcp://127.0.0.1:6379') {
        $this->address = $address;
    }

    public function __destruct() {
        if ($this->resource) {
            fclose($this->resource);
        }
    }

    protected function getResource() {
        if (!$this->resource) {
            $this->resource = stream_socket_client($this->address);
            stream_set_timeout($this->resource, self::TIMEOUT);
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
