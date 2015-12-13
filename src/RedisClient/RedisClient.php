<?php

namespace RedisClient;

use RedisClient\Command\Pipeline;
use RedisClient\Command\Traits\RedisServerCommandsTrait;
use RedisClient\Connection\ConnectionInterface;
use RedisClient\Connection\StreamConnection;
use RedisClient\Protocol\ProtocolInterface;
use RedisClient\Protocol\RedisProtocol;
use RedisClient\Command\CommandInterface;
use RedisClient\Command\Traits\RedisKeysCommandsTrait;
use RedisClient\Command\Traits\RedisStringsCommandsTrait;
use RedisClient\Command\Traits\RedisHashesCommandsTrait;
use RedisClient\Command\Traits\RedisTransactionsCommandsTrait;

class RedisClient {

    use RedisKeysCommandsTrait;
    use RedisStringsCommandsTrait;
    use RedisHashesCommandsTrait;
    use RedisTransactionsCommandsTrait;
    use RedisServerCommandsTrait
        ;

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

    public function executeCommand(CommandInterface $Command) {
        return $Command->execute($this->getRedisProtocol());
    }

    public function pipeline(\Closure $Closure = null) {
        $this->Pipeline = new Pipeline($this->getRedisProtocol());
        if ($Closure) {
            $Closure($this);
            return $this->execPipeline();
        }
        return $this;
    }

    public function execPipeline() {
        if (!$Pipeline = $this->Pipeline) {
            return false;
        }
        $this->Pipeline = null;
        return $Pipeline->execute();
    }
}
