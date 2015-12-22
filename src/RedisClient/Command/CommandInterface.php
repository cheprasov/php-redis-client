<?php

namespace RedisClient\Command;

use RedisClient\Protocol\ProtocolInterface;

interface CommandInterface {

    /**
     * @param ProtocolInterface $Protocol
     * @return mixed
     */
    public function execute(ProtocolInterface $Protocol);

    /**
     * @return string[]
     */
    public function getStructure();

}
