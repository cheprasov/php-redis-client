<?php

namespace RedisClient;

use RedisClient\Command\Pipeline;
use RedisClient\Command\Traits\AllCommandsTrait;
use RedisClient\Connection\ConnectionInterface;
use RedisClient\Connection\StreamConnection;
use RedisClient\Protocol\ProtocolInterface;
use RedisClient\Protocol\RedisProtocol;
use RedisClient\Command\CommandInterface;


class RedisClient {
    use AllCommandsTrait;

    /**
     * @var ConnectionInterface
     */
    protected $Connection;

    /**
     * @var ProtocolInterface
     */
    protected $Protocol;

    /**
     * @var Pipeline
     */
    protected $Pipeline;

    /**
     * @return StreamConnection
     */
    protected function getConnection() {
        return $this->Connection ?: $this->Connection = new StreamConnection();
    }

    /**
     * @return RedisProtocol
     */
    protected function getRedisProtocol() {
        return $this->Protocol ?: $this->Protocol = new RedisProtocol($this->getConnection());
    }

    /**
     * @param CommandInterface $Command
     * @return mixed
     */
    protected function returnCommand(CommandInterface $Command) {
        if ($this->Pipeline) {
            $this->Pipeline->addCommand($Command);
            return $this;
        } else {
            return $Command->execute($this->getRedisProtocol());
        }
    }

    /**
     * @param CommandInterface $Command
     * @return mixed
     */
    public function executeCommand(CommandInterface $Command) {
        return $Command->execute($this->getRedisProtocol());
    }

    /**
     * @param \Closure|null $Closure
     * @return $this|bool|mixed
     */
    public function pipeline(\Closure $Closure = null) {
        if ($this->Pipeline) {
            //throw new Error();
        }
        $this->Pipeline = new Pipeline($this->getRedisProtocol());
        if ($Closure) {
            $Closure($this);
            return $this->executePipeline();
        }
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function executePipeline() {
        if (!$Pipeline = $this->Pipeline) {
            return false;
        }
        $this->Pipeline = null;
        return $Pipeline->execute();
    }
}
