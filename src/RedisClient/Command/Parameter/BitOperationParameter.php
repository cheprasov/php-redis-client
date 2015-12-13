<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class BitOperationParameter extends AbstractParameter {

    const OPERATION_AND = 'AND';
    const OPERATION_OR  = 'OR';
    const OPERATION_XOR = 'XOR';
    const OPERATION_NOT = 'NOT';

    protected function normalizeParam($param) {
        $param = strtoupper((string) $param);
        if (
            $param !== self::OPERATION_AND &&
            $param !== self::OPERATION_OR  &&
            $param !== self::OPERATION_XOR &&
            $param !== self::OPERATION_NOT
        ) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

}
