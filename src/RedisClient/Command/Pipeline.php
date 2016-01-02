<?php

namespace RedisClient\Command;

class Pipeline {

    /** @var CommandInterface[] */
    protected $Commands = [];

    /**
     * @param CommandInterface $Command
     */
    public function addCommand(CommandInterface $Command) {
        $this->Commands[] = $Command;
    }

    /**
     * @return mixed
     */
    public function getStructure() {
        $structures = [];
        foreach ($this->Commands as $Command) {
            $structures[] = $Command->getStructure();
        }
        return $structures;
    }

    /**
     * @param array $responses
     * @return mixed
     */
    public function parseResponse($responses) {
        $structures = [];
        foreach ($responses as $n => $response) {
            if (!isset($this->Commands[$n])) {
                // todo: check
            }
            $structures[] = $this->Commands[$n]->parseResponse($response);
        }
        return $structures;
    }

}
