<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

/**
 * Transactions
 * @link http://redis.io/topics/transactions
 *
 * Class TransactionsCommandsTrait
 * @package RedisClient\Command\Traits
 */
trait TransactionsCommandsTrait {

    /**
     * DISCARD
     * Available since 2.0.0.
     * @link http://redis.io/commands/discard
     *
     * @return bool Always TRUE
     */
    public function discard() {
        return $this->returnCommand(
            new Command('DISCARD')
        );
    }

    /**
     * EXEC
     * Available since 1.2.0.
     * @link http://redis.io/commands/exec
     *
     * @return mixed
     */
    public function exec() {
        return $this->returnCommand(
            new Command('EXEC')
        );
    }

    /**
     * MULTI
     * Available since 1.2.0.
     * @link http://redis.io/commands/multi
     *
     * @return bool Always TRUE
     */
    public function multi() {
        return $this->returnCommand(
            new Command('MULTI')
        );
    }

    /**
     * UNWATCH
     * Available since 2.2.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/unwatch
     *
     * @return bool Always TRUE
     */
    public function unwatch() {
        return $this->returnCommand(
            new Command('UNWATCH')
        );
    }

    /**
     * WATCH key [key ...]
     * Available since 2.2.0.
     * Time complexity: O(1) for every key.
     *
     * @param string|string[] $keys
     * @return bool Always TRUE
     */
    public function watch($keys) {
        return $this->returnCommand(
            new Command('WATCH', Parameter::keys($keys))
        );
    }

}
