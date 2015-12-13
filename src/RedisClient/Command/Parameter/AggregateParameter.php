<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class AggregateParameter extends AbstractParameter {

    const PREG = '/^(SUM|MIN|MAX)$/';

    protected function normalizeParam($param) {
        $param = strtoupper((string) $param);
        if (!preg_match(static::PREG, $param)) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

}
