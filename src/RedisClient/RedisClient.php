<?php

namespace RedisClient;

use RedisClient\Pipeline\Pipeline;
use RedisClient\Command\Response\ResponseParser;
use RedisClient\Command\Traits\AllCommandsTrait;
use RedisClient\Connection\StreamConnection;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Protocol\ProtocolInterface;
use RedisClient\Protocol\RedisProtocol;

/**
 * Class RedisClient
 * @package RedisClient
 */
class RedisClient {
    use AllCommandsTrait;

    const CONFIG_SERVER = 'server';
    const CONFIG_TIMEOUT = 'timeout';
    const CONFIG_THROW_REDIS_EXCEPTIONS = 'throw-redis-exceptions';

    /**
     * Default configuration
     * @var array
     */
    protected static $defaultConfig = [
        self::CONFIG_SERVER => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
        self::CONFIG_TIMEOUT => 0.1, // in seconds
        self::CONFIG_THROW_REDIS_EXCEPTIONS => true,
    ];

    /**
     * @var ProtocolInterface
     */
    protected $Protocol;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array|null $config
     */
    public function __construct(array $config = null) {
        $this->config = $config ? array_merge(static::$defaultConfig, $config) : static::$defaultConfig;
    }

    /**
     * @param string|null $param
     * @return mixed|null
     */
    protected function getConfig($param = null) {
        if (!$param) {
            return $this->config;
        }
        return empty($this->config[$param]) ? null : $this->config[$param];
    }

    /**
     * @return ProtocolInterface
     */
    protected function getProtocol() {
        if (!$this->Protocol) {
            $this->Protocol = new RedisProtocol(
                new StreamConnection(
                    $this->getConfig(self::CONFIG_SERVER),
                    $this->getConfig(self::CONFIG_TIMEOUT)
                )
            );
        }
        return $this->Protocol;
    }

    /**
     * @inheritdoc
     */
    protected function returnCommand(array $command, array $params = null, $parserId = null) {
        return $this->executeCommand($command, $params, $parserId);
    }

    /**
     * @param array $command
     * @param array|null $params
     * @param int|null $parserId
     * @return mixed
     * @throws ErrorResponseException
     */
    public function executeCommand(array $command, array $params = null, $parserId = null) {
        $response = $this->getProtocol()->send($this->getStructure($command, $params));
        if (is_object($response)) {
            if ($response instanceof ErrorResponseException) {
                throw $response;
            }
        }
        if (isset($parserId)) {
            return ResponseParser::parse($parserId, $response);
        }
        return $response;
    }

    /**
     * @param null|Pipeline|\Closure $Pipeline
     * @return mixed|Pipeline
     * @throws \InvalidArgumentException
     */
    public function pipeline($Pipeline = null) {
        if (!$Pipeline) {
            return new Pipeline();
        }
        if ($Pipeline instanceof \Closure) {
            $Pipeline = new Pipeline($Pipeline);
        }
        if ($Pipeline instanceof Pipeline) {
            return $this->executePipeline($Pipeline);
        }
        throw new \InvalidArgumentException();
    }

    /**
     * @param Pipeline $Pipeline
     * @return mixed
     * @throws ErrorResponseException
     */
    protected function executePipeline(Pipeline $Pipeline) {
        $responses = $this->getProtocol()->sendMulti($Pipeline->getStructure());
        if (is_object($responses)) {
            if ($responses instanceof ErrorResponseException) {
                throw $responses;
            }
        }
        return $Pipeline->parseResponse($responses);
    }

    /**
     * @param string[] $command
     * @param array|null $params
     * @return string[]
     */
    protected function getStructure(array $command, array $params = null) {
        if (!isset($params)) {
            return $command;
        }
        foreach ($params as $param) {
            if (is_array($param)) {
                foreach($param as $p) {
                    $command[] = $p;
                }
            } else {
                $command[] = $param;
            }
        }
        return $command;
    }

}
