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
namespace RedisClient\Pipeline;

use RedisClient\Command\Response\ResponseParser;
use RedisClient\Exception\ErrorException;

abstract class AbstractPipeline implements PipelineInterface {

    /**
     * @var array[]
     */
    protected $commandLines = [];

    /**
     * @param \Closure|null $Closure
     */
    public function __construct(\Closure $Closure = null) {
        if ($Closure) {
            $Closure($this);
        }
    }

    /**
     * @inheritdoc
     * @return $this
     */
    protected function returnCommand(array $command, array $params = null, $parserId = null) {
        $this->commandLines[] = [$command, $params, $parserId];
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function subscribeCommand(array $subCommand, array $unsubCommand, array $params = null, $callback) {
        throw new ErrorException('Do not use subscribe in pipeline');
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
        foreach ($responses as $n => $response) {
            if (empty($this->commandLines[$n][2])) {
                // todo: check
                continue;
            }
            $responses[$n] = ResponseParser::parse($this->commandLines[$n][2], $response);
        }
        return $responses;
    }

}
