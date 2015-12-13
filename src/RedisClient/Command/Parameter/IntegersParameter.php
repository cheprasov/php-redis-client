<?php

namespace RedisClient\Command\Parameter;

class IntegersParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        $param = (array) $param;
        array_walk($param, function($int) {
            return (int) $int;
        });
        return $param;
    }

}
