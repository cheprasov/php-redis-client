<?php

namespace RedisClient\Command\Response;

interface ResponseParserInterface {

    /**
     * @param mixed $response
     * @return mixed
     */
    public function parseResponse($response);

}
