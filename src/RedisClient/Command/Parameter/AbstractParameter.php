<?php

namespace RedisClient\Command\Parameter;

abstract class AbstractParameter implements ParameterInterface {

    /**
     * @var mixed
     */
    protected $parameter;

    /**
     * @param mixed $param
     */
    public function __construct($param) {
        $this->parameter = $this->normalizeParam($param);
    }

    /**
     * @param mixed $param
     * @return mixed
     */
    protected function normalizeParam($param) {
        return $param;
    }

    /**
     * @return mixed
     */
    public function getStructure() {
        return $this->parameter;
    }

}
