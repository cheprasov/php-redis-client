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
namespace RedisClient\Pipeline;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Command\Response\ResponseParser;
use RedisClient\Exception\CommandNotFoundException;
use RedisClient\Exception\ErrorException;

abstract class AbstractPipeline implements PipelineInterface {

    /**
     * @var array[]
     */
    protected $commandLines = [];

    /**
     * @var string
     */
    protected $keys = [];

    /**
     * @var int
     */
    protected $transactionMode = AbstractRedisClient::TRANSACTION_MODE_NONE;

    /**
     * @param \Closure|null $Closure
     */
    public function __construct(\Closure $Closure = null) {
        if ($Closure) {
            $Closure($this);
        }
    }

    /**
     * @param string|string[] $keys
     */
    protected function addKeys($keys) {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->keys[$key] = $key;
            }
        } else {
            $this->keys[$keys] = $keys;
        }
    }

    /**
     * @return string[]
     */
    public function getKeys() {
        return array_values($this->keys);
    }

    /**
     * @inheritdoc
     * @return $this
     */
    protected function returnCommand(array $command, $keys = null, array $params = null, $parserId = null) {
        $this->commandLines[] = [$command, $params, $parserId];
        if ($keys) {
            $this->addKeys($keys);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function subscribeCommand(array $subCommand, array $unsubCommand, array $params = null, $callback) {
        throw new ErrorException('Do not use subscribe in pipeline');
    }

    /**
     * @param int $transactionMode
     */
    protected function setTransactionMode($transactionMode) {
        $this->transactionMode = $transactionMode;
    }

    /**
     * @return array[]
     */
    public function getStructure() {
        $structures = [];
        foreach ($this->commandLines as $commandLine) {
            $structure = $commandLine[0];
            if (isset($commandLine[1])) {
                foreach ($commandLine[1] as $params) {
                    if (is_array($params)) {
                        foreach ($params as $param) {
                            $structure[] = $param;
                        }
                    } else {
                        $structure[] = $params;
                    }
                }
            }
            $structures[] = $structure;
        }
        return $structures;
    }

    /**
     * @param array $responses
     * @return mixed
     */
    public function parseResponse($responses) {
        if ($this->transactionMode === AbstractRedisClient::TRANSACTION_MODE_NONE) {
            foreach ($responses as $n => $response) {
                if (empty($this->commandLines[$n][2])) {
                    // todo: check
                    continue;
                }
                $responses[$n] = ResponseParser::parse($this->commandLines[$n][2], $response);
            }
            return $responses;
        }

        if ($this->transactionMode === AbstractRedisClient::TRANSACTION_MODE_EXECUTED) {
            $this->transactionMode = AbstractRedisClient::TRANSACTION_MODE_NONE;
            $execResponses = array_pop($responses);
            foreach ($execResponses as $n => $response) {
                // MULTI command is ignored
                if (empty($this->commandLines[$n + 1][2])) {
                    continue;
                }
                $execResponses[$n] = ResponseParser::parse($this->commandLines[$n + 1][2], $response);
            }
            $responses[] = $execResponses;
        }

        return $responses;
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
