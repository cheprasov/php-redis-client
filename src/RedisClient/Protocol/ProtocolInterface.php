<?php

namespace RedisClient\Protocol;

interface ProtocolInterface {

    /**
     * @param string[] $structure
     * @return mixed
     */
    public function send(array $structure);

    /**
     * @param array[] $structures
     * @return mixed
     */
    public function sendMulti(array $structures);

}
