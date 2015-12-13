<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class NXOrXXParameter extends AbstractParameter {

    const NX = 'NX';
    const XX = 'XX';

    protected function normalizeParam($param) {
        $param = strtoupper((string) $param);
        if ($param !== self::XX && $param !== self::NX) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

}
