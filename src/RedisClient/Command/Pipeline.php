<?php

namespace RedisClient\Command;

use RedisClient\Protocol\ProtocolInterface;

class Pipeline {

    /** @var ProtocolInterface  */
    protected $Protocol;

    /** @var CommandInterface[] */
    protected $Commands = [];

    /**
     * @param ProtocolInterface $Protocol
     */
    public function __construct(ProtocolInterface $Protocol) {
        $this->Protocol = $Protocol;
    }

    /**
     * @param CommandInterface $Command
     */
    public function addCommand(CommandInterface $Command) {
        $this->Commands[] = $Command;
    }

    /**
     * @return mixed
     */
    public function execute() {
        $structures = [];
        foreach ($this->Commands as $Command) {
            $structures[] = $Command->getStructure();
        }
        return call_user_func_array([$this->Protocol, 'send'], $structures);
    }

}
