<?php

namespace RedisClient\Command\Response;

use RedisClient\Pattern\Traits\SingletonTrait;

class IntegerResponseParser implements ResponseParserInterface {
    use SingletonTrait;

    public function parseResponse($response) {
        return (int) $response;
    }

}
