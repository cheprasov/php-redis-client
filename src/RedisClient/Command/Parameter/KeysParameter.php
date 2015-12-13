<?php

namespace RedisClient\Command\Parameter;

class KeysParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        $param = (array) $param;
        foreach ($param as $n => $key) {
            if (strlen($key) === 0) {
                throw new \InvalidArgumentException($param);
            }
        }
        return $param;
    }

}
