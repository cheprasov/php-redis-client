<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class AssocArrayParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        if (!is_array($param)) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

    public function getStructure() {
        $structure = [];
        foreach ($this->parameter as $key => $value) {
            $structure[] = $key;
            $structure[] = $value;
        }
        return $structure;
    }

}
