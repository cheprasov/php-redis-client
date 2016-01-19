<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <cheprasov.84@ya.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RedisClient\Client;

use RedisClient\Command\Response\ResponseParser;
use RedisClient\Connection\StreamConnection;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Pipeline\Pipeline;
use RedisClient\Pipeline\PipelineInterface;
use RedisClient\Protocol\ProtocolInterface;
use RedisClient\Protocol\RedisProtocol;

/**
 * Class RedisClient
 * @package RedisClient
 */
abstract class AbstractRedisClient {

    const VERSION = '1.0.0';

    const CONFIG_SERVER = 'server';
    const CONFIG_TIMEOUT = 'timeout';

    /**
     * Default configuration
     * @var array
     */
    protected static $defaultConfig = [
        self::CONFIG_SERVER => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
        self::CONFIG_TIMEOUT => 1, // in seconds
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
    protected function executeCommand(array $command, array $params = null, $parserId = null) {
        $response = $this->getProtocol()->send($this->getStructure($command, $params));
        if ($response instanceof ErrorResponseException) {
            throw $response;
        }
        if (isset($parserId)) {
            return ResponseParser::parse($parserId, $response);
        }
        return $response;
    }

    /**
     * @param string[] $structure
     * @return mixed
     * @throws ErrorResponseException
     */
    public function executeRaw($structure) {
        $response = $this->getProtocol()->send($structure);
        if ($response instanceof ErrorResponseException) {
            throw $response;
        }
        return $response;
    }

    /**
     * @param string $stringCommand
     * @return mixed
     * @throws ErrorResponseException
     */
    public function executeRawString($stringCommand) {
        return $this->executeRaw(explode(' ', $stringCommand));
    }

    /**
     * @inheritdoc
     */
    protected function subscribeCommand(array $subCommand, array $unsubCommand, array $params = null, $callback) {
        $this->getProtocol()->subscribe($this->getStructure($subCommand, $params), $callback);
        return $this->executeCommand($unsubCommand, $params);
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

    /**
     * @param null|Pipeline|\Closure $Pipeline
     * @return mixed|Pipeline
     * @throws \InvalidArgumentException
     */
    public function pipeline($Pipeline = null) {
        if (!$Pipeline) {
            return $this->createPipeline();
        }
        if ($Pipeline instanceof \Closure) {
            $Pipeline = $this->createPipeline($Pipeline);
        }
        if ($Pipeline instanceof PipelineInterface) {
            return $this->executePipeline($Pipeline);
        }
        throw new \InvalidArgumentException();
    }

    /**
     * @param \Closure|null $Pipeline
     * @return PipelineInterface
     */
    abstract protected function createPipeline(\Closure $Pipeline = null);

    /**
     * @param PipelineInterface $Pipeline
     * @return mixed
     * @throws ErrorResponseException
     */
    protected function executePipeline(PipelineInterface $Pipeline) {
        $responses = $this->getProtocol()->sendMulti($Pipeline->getStructure());
        if (is_object($responses)) {
            if ($responses instanceof ErrorResponseException) {
                throw $responses;
            }
        }
        return $Pipeline->parseResponse($responses);
    }

}
