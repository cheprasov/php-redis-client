<?php

namespace RedisClient\Command\Parameter;

class FloatParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        return (float) $param;
    }

}
