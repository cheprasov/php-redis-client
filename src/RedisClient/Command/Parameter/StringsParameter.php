<?php

namespace RedisClient\Command\Parameter;

class StringsParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        return (array) $param;
    }

}
