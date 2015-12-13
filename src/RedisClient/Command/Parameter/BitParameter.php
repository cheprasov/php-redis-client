<?php

namespace RedisClient\Command\Parameter;

class BitParameter extends AbstractParameter {

    protected function normalizeParam($param) {
        return (int) ((bool) $param);
    }

}
