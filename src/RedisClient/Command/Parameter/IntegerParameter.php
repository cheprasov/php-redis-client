<?php

namespace RedisClient\Command\Parameter;

class IntegerParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        return (int) $param;
    }

}
