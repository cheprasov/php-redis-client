<?php

namespace RedisClient\Command\Response;

use RedisClient\Pattern\Traits\SingletonTrait;

class AssocArrayResponseParser implements ResponseParserInterface {
    use SingletonTrait;

    public function parseResponse($response) {
        $array = [];
        for ($i = 0, $count = count($response); $i < $count; $i += 2) {
            $array[$response[$i]] = $response[$i + 1];
        }
        return $array;
    }

}
