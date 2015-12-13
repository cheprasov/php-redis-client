<?php

namespace RedisClient\Command\Parameter;

class StringParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        return (string) $param;
    }

}
