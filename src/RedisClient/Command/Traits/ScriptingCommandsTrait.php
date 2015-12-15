<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\IntegerParameter;
use RedisClient\Command\Parameter\KeysParameter;
use RedisClient\Command\Parameter\StringParameter;
use RedisClient\Command\Parameter\StringsParameter;

/**
 * Scripting
 * @link http://redis.io/commands#scripting
 */
trait ScriptingCommandsTrait {

    /**
     * EVAL script numkeys key [key ...] arg [arg ...]
     * Available since 2.6.0.
     * Time complexity: Depends on the script that is executed.
     *
     * @param string $script
     * @param array|null $keys
     * @param array|null $args
     * @return mixed
     */
    public function evalScript($script, $keys = null, $args = null) {
        $params = [
            new StringParameter($script)
        ];
        if (is_array($keys)) {
            $params[] = new IntegerParameter(count($keys));
            $params[] = new KeysParameter($keys);
        } else {
            $params[] = new IntegerParameter(0);
        }
        if (is_array($args)) {
            $params[] = new StringsParameter($args);
        }
        return $this->returnCommand(
            new Command('EVAL', $params)
        );
    }

    /**
     * EVALSHA sha1 numkeys key [key ...] arg [arg ...]
     * Available since 2.6.0.
     * Time complexity: Depends on the script that is executed.
     *
     * @param string $sha
     * @param array|null $keys
     * @param array|null $args
     * @return mixed
     */
    public function evalsha($sha, $keys = null, $args = null) {
        $params = [
            new StringParameter($sha)
        ];
        if (is_array($keys)) {
            $params[] = new IntegerParameter(count($keys));
            $params[] = new KeysParameter($keys);
        } else {
            $params[] = new IntegerParameter(0);
        }
        if (is_array($args)) {
            $params[] = new IntegerParameter($args);
        }
        return $this->returnCommand(
            new Command('EVALSHA', $params)
        );
    }

    /**
     * SCRIPT EXISTS script [script ...]
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the number of scripts to check
     * (so checking a single script is an O(1) operation).
     *
     * @param string|string[] $script
     * @return int|int[]
     */
    public function scriptExists($script) {
        return $this->returnCommand(
            new Command(
                'SCRIPT EXISTS',
                new StringsParameter($script),
                is_array($script) ? null : function($result) {return $result[0];}
            )
        );
    }

    /**
     * SCRIPT FLUSH
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the number of scripts in cache
     *
     * @return bool
     */
    public function scriptFlush() {
        return $this->returnCommand(
            new Command('SCRIPT FLUSH')
        );
    }

    /**
     * SCRIPT KILL
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @return bool
     */
    public function scriptKill() {
        return $this->returnCommand(
            new Command('SCRIPT KILL')
        );
    }

    /**
     * SCRIPT LOAD script
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the length in bytes of the script body.
     *
     * @param string $script
     * @return string
     */
    public function scriptLoad($script) {
        return $this->returnCommand(
            new Command('SCRIPT LOAD', new StringParameter($script))
        );
    }

}
