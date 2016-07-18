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
use RedisClient\RedisClient;

abstract class AbstractRedisClient {

    const VERSION = '1.4.0';

    const CONFIG_SERVER = 'server';
    const CONFIG_TIMEOUT = 'timeout';
    const CONFIG_DATABASE = 'database';
    const CONFIG_PASSWORD = 'password';

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
        return isset($this->config[$param]) ? $this->config[$param] : null;
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
            $this->onProtocolInit();
        }
        return $this->Protocol;
    }

    protected function onProtocolInit() {
        /** @var RedisClient $this */
        if ($password = $this->getConfig(self::CONFIG_PASSWORD)) {
            $this->auth($password);
        }
        if ($db = (int) $this->getConfig(self::CONFIG_DATABASE)) {
            $this->select($db);
        }
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
     * Command will parsed by the client, before sent to server
     * @param string $command
     * @return mixed
     */
    public function executeRawString($command) {
        return $this->executeRaw($this->parseRawString($command));
    }

    /**
     * @param string $command
     * @return string[]
     */
    public function parseRawString($command) {
        $structure = [];
        $line = ''; $quotes = false;
        for ($i = 0, $length = strlen($command); $i <= $length; ++$i) {
            if ($i === $length) {
                if (isset($line[0])) {
                    $structure[] = $line;
                    $line = '';
                }
                break;
            }
            if ($command[$i] === '"' && $i && $command[$i - 1] !== '\\') {
                $quotes = !$quotes;
                if (!$quotes && !isset($line[0]) && $i + 1 === $length) {
                    $structure[] = $line;
                    $line = '';
                }
            } else if ($command[$i] === ' ' && !$quotes) {
                if (isset($command[$i + 1]) && trim($command[$i + 1])) {
                    if (count($structure) || isset($line[0])) {
                        $structure[] = $line;
                        $line = '';
                    }
                }
            } else {
                $line .= $command[$i];
            }
        }
        array_walk($structure, function(&$line) {
            $line = str_replace('\\"', '"', $line);
        });
        return $structure;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name , array $arguments) {
        if ($method = $this->getMethodNameForReservedWord($name)) {
            return call_user_func_array([$this, $method], $arguments);
        }
        throw new \Exception('Call to undefined method '. static::class. '::'. $name);
    }

}
