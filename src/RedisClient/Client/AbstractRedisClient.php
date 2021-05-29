<?php
/**
 * This file is part of RedisClient.
 * git: https://github.com/cheprasov/php-redis-client
 *
 * (C) Alexander Cheprasov <acheprasov84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RedisClient\Client;

use RedisClient\Cluster\ClusterMap;
use RedisClient\Command\Response\ResponseParser;
use RedisClient\Exception\AskResponseException;
use RedisClient\Exception\CommandNotFoundException;
use RedisClient\Exception\ErrorResponseException;
use RedisClient\Exception\InvalidArgumentException;
use RedisClient\Exception\MovedResponseException;
use RedisClient\Exception\TryAgainResponseException;
use RedisClient\Pipeline\Pipeline;
use RedisClient\Pipeline\PipelineInterface;
use RedisClient\Protocol\ProtocolFactory;
use RedisClient\Protocol\ProtocolInterface;
use RedisClient\RedisClient;

abstract class AbstractRedisClient {

    const VERSION = '1.10.0';

    const CONFIG_SERVER = 'server';
    const CONFIG_TIMEOUT = 'timeout';
    const CONFIG_DATABASE = 'database';
    const CONFIG_PASSWORD = 'password';
    const CONFIG_CLUSTER = 'cluster';
    const CONFIG_VERSION = 'version';
    const CONFIG_CONNECTION = 'connection';

    const TRANSACTION_RESPONSE_QUEUED = 'QUEUED';

    const TRANSACTION_MODE_NONE = 0;
    const TRANSACTION_MODE_STARTED = 1;
    const TRANSACTION_MODE_EXECUTED = 2;

    /**
     * Default configuration
     * @var array
     */
    protected static $defaultConfig = [
        self::CONFIG_SERVER => '127.0.0.1:6379', // or tcp://127.0.0.1:6379 or 'unix:///tmp/redis.sock'
        self::CONFIG_TIMEOUT => 1, // in seconds
        self::CONFIG_DATABASE => 0, // default db
        self::CONFIG_CLUSTER => [
            'enabled' => false,
            'clusters' => [],
            'init_on_start' => false,
            'init_on_error_moved' => false,
            'timeout_on_error_tryagain' => 0.05,
        ],
        self::CONFIG_CONNECTION => null,
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
     * @var ClusterMap
     */
    protected $ClusterMap;


    /**
     * @var int
     */
    protected $transactionMode = self::TRANSACTION_MODE_NONE;

    /**
     * @var array[]|null
     */
    protected $responseParsers = null;

    /**
     * @param array|null $config
     */
    public function __construct(array $config = null) {
        $this->setConfig($config);
    }

    /**
     * @param array|null $config
     */
    protected function setConfig(array $config = null) {
        $this->config = $config ? array_merge(static::$defaultConfig, $config) : static::$defaultConfig;
        if (!empty($this->config[self::CONFIG_CLUSTER]['enabled'])) {
            $ClusterMap = new ClusterMap($this, $this->getConfig());
            if (!empty($this->config[self::CONFIG_CLUSTER]['clusters'])) {
                $ClusterMap->setClusters($this->config[self::CONFIG_CLUSTER]['clusters']);
            }
            $this->ClusterMap = $ClusterMap;
            if (!empty($this->config[self::CONFIG_CLUSTER]['init_on_start'])) {
                $this->_syncClusterSlotsFromRedisServer();
            }
        }
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
     * @param AbstractRedisClient $RedisClient
     * @param $config
     * @return \RedisClient\Protocol\ProtocolInterface
     */
    protected function createProtocol(AbstractRedisClient $RedisClient, $config)
    {
        return ProtocolFactory::createRedisProtocol($RedisClient, $config);
    }

    /**
     * @return ProtocolInterface
     */
    protected function getProtocol() {
        if (!$this->Protocol) {
            $this->Protocol = $this->createProtocol($this, $this->getConfig());
            if ($this->ClusterMap) {
                $this->ClusterMap->addProtocol($this->Protocol);
            }
        }
        return $this->Protocol;
    }

    /**
     *
     */
    public function _syncClusterSlotsFromRedisServer() {
        /** @var RedisClient $this */
        $response = $this->clusterSlots();
        $clusters = ResponseParser::parseClusterSlots($response);
        $this->ClusterMap->setClusters($clusters);
    }

    /**
     * @inheritdoc
     */
    protected function returnCommand(array $command, $keys = null, array $params = null, $parserId = null) {
        return $this->executeCommand($command, $keys, $params, $parserId);
    }

    /**
     * @param null|string|string[] $command
     * @param array|null $params
     * @param int|null $parserId
     * @return mixed
     * @throws ErrorResponseException
     */
    protected function executeCommand(array $command, $keys, array $params = null, $parserId = null) {
        $Protocol = $this->getProtocolByKey($keys);
        $response = $this->executeProtocolCommand($Protocol, $command, $params);

        if ($response instanceof ErrorResponseException) {
            throw $response;
        }

        if ($this->transactionMode === self::TRANSACTION_MODE_NONE) {
            if (isset($parserId)) {
                return ResponseParser::parse($parserId, $response);
            }
        } else {
            if ($this->transactionMode === self::TRANSACTION_MODE_STARTED) {
                if ($response === self::TRANSACTION_RESPONSE_QUEUED) {
                    $this->responseParsers[] = $parserId;
                }
            } elseif ($this->transactionMode === self::TRANSACTION_MODE_EXECUTED) {
                $this->setTransactionMode(self::TRANSACTION_MODE_NONE);
                if (is_array($response) && count($this->responseParsers)
                    && count($this->responseParsers) === count($response)) {
                    return array_map([ResponseParser::class, 'parse'], $this->responseParsers, $response);
                }
            }
        }

        return $response;
    }

    /**
     * @param ProtocolInterface $Protocol
     * @param array $command
     * @param array|null $params
     * @return mixed
     * @throws ErrorResponseException
     */
    protected function executeProtocolCommand(ProtocolInterface $Protocol, array $command, array $params = null) {
        $response = $Protocol->send($this->getStructure($command, $params));

        if ($response instanceof ErrorResponseException && $this->ClusterMap) {
            if ($response instanceof MovedResponseException) {
                $conf = $this->getConfig(self::CONFIG_CLUSTER);
                if (!empty($conf['init_on_error_moved'])) {
                    $this->_syncClusterSlotsFromRedisServer();
                } else {
                    $this->ClusterMap->addCluster($response->getSlot(), $response->getServer());
                }
                $this->Protocol = $this->ClusterMap->getProtocolByServer($response->getServer());
                return $this->executeProtocolCommand($this->Protocol, $command, $params);
            }
            if ($response instanceof AskResponseException) {
                $config = $this->getConfig();
                $config['server'] = $response->getServer();
                $TempRedisProtocol = $this->createProtocol($this, $config);
                $TempRedisProtocol->send(['ASKING']);
                return $this->executeProtocolCommand($TempRedisProtocol, $command, $params);
            }
            if ($response instanceof TryAgainResponseException) {
                $config = $this->getConfig(self::CONFIG_CLUSTER);
                if (isset($config['timeout_on_error_tryagain'])) {
                    usleep($config['timeout_on_error_tryagain'] * 1000000);
                }
                return $this->executeProtocolCommand($Protocol, $command, $params);
            }
        }

        return $response;
    }

    /**
     * @param string|string[] $keys
     * @return ProtocolInterface
     */
    protected function getProtocolByKey($keys) {
        if (isset($keys) && $this->ClusterMap) {
            $key = is_array($keys) ? $keys[0] : $keys;
            if ($Protocol = $this->ClusterMap->getProtocolByKey($key)) {
                return $this->Protocol = $Protocol;
            }
        }
        return $this->getProtocol();
    }

    /**
     * @param PipelineInterface $Pipeline
     * @return mixed
     * @throws ErrorResponseException
     */
    protected function executePipeline(PipelineInterface $Pipeline) {
        $Protocol = $this->getProtocolByKey($Pipeline->getKeys());
        $responses = $Protocol->sendMulti($Pipeline->getStructure());
        if (is_object($responses)) {
            if ($responses instanceof ErrorResponseException) {
                throw $responses;
            }
        }
        return $Pipeline->parseResponse($responses);
    }

    /**
     * @inheritdoc
     */
    protected function subscribeCommand(array $subCommand, array $unsubCommand, array $params = null, $callback) {
        $Protocol = $this->getProtocol();
        $Protocol->subscribe($this->getStructure($subCommand, $params), $callback);
        return $this->executeProtocolCommand($Protocol, $unsubCommand, $params);
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
     * @param int $transactionMode
     */
    protected function setTransactionMode($transactionMode) {
        $this->transactionMode = $transactionMode;
    }

    /**
     * @param null|Pipeline|\Closure $Pipeline
     * @return mixed|Pipeline
     * @throws InvalidArgumentException
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
        throw new InvalidArgumentException();
    }

    /**
     * @param \Closure|null $Pipeline
     * @return PipelineInterface
     */
    abstract protected function createPipeline(\Closure $Pipeline = null);

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
     * @deprecated
     * Command will parsed by the client, before sent to server
     * @param string $command
     * @return mixed
     */
    public function executeRawString($command) {
        return $this->executeRaw($this->parseRawString($command));
    }

    /**
     * @deprecated
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
        throw new CommandNotFoundException('Call to undefined method '. static::class. '::'. $name);
    }

}
