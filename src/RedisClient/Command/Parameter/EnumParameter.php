<?php

namespace RedisClient\Command\Parameter;

use InvalidArgumentException;

class EnumParameter extends AbstractParameter {

    /**
     * @var string[]|int[]
     */
    protected $enum;

    /**
     * @param string|int $param
     * @param string[]|int[] $enum
     * @param int|string|null $default
     */
    public function __construct($param, $enum, $default = null) {
        $this->enum = (array) $enum;
        if (!$param && is_int($default)) {
            $param = $this->enum[$default];
        }
        parent::__construct($param);
    }

    /**
     * @inheritdoc
     */
    protected function normalizeParam($param) {
        $param = strtoupper((string) $param);
        if (!in_array($param, $this->enum)) {
            throw new InvalidArgumentException($param);
        }
        return $param;
    }

}
