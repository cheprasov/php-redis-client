<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class LimitParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        if (is_numeric($param)) {
            return [
                'offset' => 0,
                'count'  => (int) $param
            ];
        }
        if (is_array($param) && isset($param['count'])) {
            return [
                'offset' => empty($param['offset']) ? 0: (int) $param['offset'],
                'count'  => (int) $param['count'],
            ];
        }
        if ($param && is_string($param) && preg_match('/^-?\d+\s+-?\d+$/', $param)) {
            $param = preg_split('/\s+/', trim($param), 2);
        }
        if (is_array($param)) {
            if (isset($param[0]) && isset($param[1])) {
                return [
                    'offset' => (int) $param[0],
                    'count'  => (int) $param[1],
                ];
            }
            if (isset($param[0]) && !isset($param[1])) {
                return [
                    'offset' => 0,
                    'count'  => (int) $param[0],
                ];
            }
        }
        throw new InvalidArgumentException($param);
    }

    public function getStructure() {
        return [
            $this->parameter['offset'],
            $this->parameter['count'],
        ];
    }

}
