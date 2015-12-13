<?php

namespace RedisClient\Command\Parameter;

class MinMaxParameter extends AbstractParameter {

    const PREG = "/^([-+]inf|\(?\d+)$/";

    protected function normalizeParam($param) {
        $param = trim($param);
        if (!preg_match(static::PREG, $param)) {
            new \InvalidArgumentException($param);
        }
        return $param;
    }

}
