<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class AddressParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        if ($param && is_string($param)) {
            $param = explode(':', trim($param), 2);
        }
        if (is_array($param)) {
            if (isset($param[0]) && isset($param[1])) {
                return [
                    'ip' => $param[0],
                    'port' => $param[1],
                ];
            } elseif (isset($param['ip']) && isset($param['port'])) {
                return [
                    'ip' => $param['ip'],
                    'port'  => $param['port'],
                ];
            }
        }
        throw new InvalidArgumentException($param);
    }

    public function getStructure() {
        return [
            $this->parameter['ip'],
            $this->parameter['port'],
        ];
    }

}
