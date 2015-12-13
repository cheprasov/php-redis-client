<?php

namespace RedisClient\Command\Parameter;
use RedisClient\Command\Command;

class CommandParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        if (!$param instanceof Command) {
            throw new \InvalidArgumentException('Param [command] must be instance of '. Command::class);
        }
        return $param;
    }

    /**
     * @return mixed
     */
    public function getStructure() {
        return $this->parameter->getStructure();
    }

}
