<?php

namespace RedisClient\Protocol;

interface ProtocolInterface {

    /**
     * @param string[] $structures
     * @param bool $multi
     * @return mixed
     */
    public function send($structures, $multi = false);

}
