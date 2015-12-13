<?php

namespace RedisClient\Command\Parameter;

class SpecifyIntervalParameter extends AbstractParameter {

    const PREG = "/^(-|+|[\(\[]\w)$/";

    protected function normalizeParam($param) {
        $param = trim($param);
        if (!preg_match(static::PREG, $param)) {
            new \InvalidArgumentException($param);
        }
        return $param;
    }

}
