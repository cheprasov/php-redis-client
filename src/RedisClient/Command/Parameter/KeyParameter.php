<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class KeyParameter extends StringParameter {

    protected function normalizeParam($param) {
        $param = parent::normalizeParam($param);
        if (strlen($param) === 0) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

}
