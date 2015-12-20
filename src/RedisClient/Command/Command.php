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
    protected $parameters = [];

    /**
     * @var \Closure|ResponseParserInterface
     */
    protected $ResponseParser;

    /**
     * @param string $command
     * @param mixed $parameters
     * @param ResponseParserInterface|\Closure|null $ResponseParser
     */
    public function __construct($command, $parameters = null, $ResponseParser = null) {
        $this->command = $command;
        if ($parameters) {
            if (is_array($parameters)) {
                $this->addParameters($parameters);
            } else {
                $this->addParameter($parameters);
            }
        }
        if ($ResponseParser) {
            $this->ResponseParser = $ResponseParser;
        }
    }

    /**
     * @param mixed $Parameter
     */
    protected function addParameter(
        $Parameter) {
        $this->parameters[] = $Parameter;
    }

    /**
     * @param array $Parameters
     */
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
     * @return string[]
     */
    public function getStructure() {
        $structure = preg_split('/\s+/', $this->command);

        foreach ($this->parameters as $parameter) {
            if (is_array($parameter)) {
                $structure = array_merge($structure, $parameter);
            } else {
                $structure[] = $parameter;
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
