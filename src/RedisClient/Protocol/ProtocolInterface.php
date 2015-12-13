<?php

namespace RedisClient\Protocol;

interface ProtocolInterface {

    /**
     * @param string $structure
     * @return mixed
     */
    public function send($structure);

}
