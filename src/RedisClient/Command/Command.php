<?php

namespace RedisClient\Command;

use RedisClient\Command\Parameter\ParameterInterface;
use RedisClient\Command\Response\ResponseParserInterface;
use RedisClient\Protocol\ProtocolInterface;

class Command implements CommandInterface {

    /**
     * @var string
     */
    protected $command;

    /**
     * @var ParameterInterface[]
     */
    protected $Parameters = [];

    /**
     * @var \Closure|ResponseParserInterface
     */
    protected $ResponseParser;

    /**
     * @param string $command
     * @param ParameterInterface|ParameterInterface[] $Parameters
     * @param ResponseParserInterface|\Closure|null $ResponseParser
     */
    public function __construct($command, $Parameters = null, $ResponseParser = null) {
        $this->command = $command;
        if ($Parameters) {
            if (is_array($Parameters)) {
                $this->addParameters($Parameters);
            } else {
                $this->addParameter($Parameters);
            }
        }
        if ($ResponseParser) {
            $this->ResponseParser = $ResponseParser;
        }
    }

    protected function addParameter(ParameterInterface $Parameter) {
        $this->Parameters[] = $Parameter;
    }

    protected function addParameters(array $Parameters) {
        foreach ($Parameters as $Parameter) {
            $this->addParameter($Parameter);
        }
    }

    /**
     * @return string
     */
    public function getCommand() {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getStructure() {
        $structure = preg_split('/\s+/', $this->command);

        foreach ($this->Parameters as $Parameter) {
            /** @var ParameterInterface $Parameter */
            $param = $Parameter->getStructure();
            if (is_array($param)) {
                $structure = array_merge($structure, $param);
            } else {
                $structure[] = $param;
            }
        }

        return $structure;
    }

    /**
     * @inheritdoc
     */
    public function execute(ProtocolInterface $Protocol) {
        $command = $this->getStructure();
        $response = $Protocol->send($command);
        $response = $this->parseResponse($response);
        $result = $this->processResponse($response);
        return $result;
    }

    /**
     * @param mixed $response
     * @return mixed
     */
    protected function parseResponse($response) {
        if ($this->ResponseParser instanceof ResponseParserInterface) {
            return $this->ResponseParser->parseResponse($response);
        } elseif ($this->ResponseParser instanceof \Closure) {
            $Parser = $this->ResponseParser;
            return $Parser($response);
        }
        return $response;
    }

    /**
     * @param mixed $response
     * @return mixed
     */
    public function processResponse($response) {
        return $response;
    }

}
