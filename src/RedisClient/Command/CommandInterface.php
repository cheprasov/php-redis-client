<?php

namespace RedisClient\Command;

interface CommandInterface {

    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string[]
     */
    public function getStructure();

    /**
     * @param mixed $response
     * @return mixed
     */
    public function parseResponse($response);

}
