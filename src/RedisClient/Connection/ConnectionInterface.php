<?php

namespace RedisClient\Connection;

interface ConnectionInterface {

    /**
     * @param string $string
     * @return int|null
     */
    public function write($string);

    /**
     * @return string|null
     */
    public function readLine();

    /**
     * @param int $length
     * @return string|null
     */
    public function read($length);

}
