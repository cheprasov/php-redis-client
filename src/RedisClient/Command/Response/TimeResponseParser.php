<?php

namespace RedisClient\Command\Response;

use RedisClient\Pattern\Traits\SingletonTrait;

class TimeResponseParser implements ResponseParserInterface {
    use SingletonTrait;

    public function parseResponse($response) {
        if (is_array($response) && count($response) === 2) {
            return implode('.', $response);
        }
        return $response;
    }

}
