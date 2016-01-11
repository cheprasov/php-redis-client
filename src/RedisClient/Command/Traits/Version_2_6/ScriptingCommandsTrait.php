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
namespace RedisClient\Command\Traits\Version_2_6;

use RedisClient\Command\Parameter\Parameter;

/**
 * ScriptingCommandsTrait
 * @link http://redis.io/commands#scripting
 */
trait ScriptingCommandsTrait {

    /**
     * EVAL script numkeys key [key ...] arg [arg ...]
     * Available since 2.6.0.
     * Time complexity: Depends on the script that is executed.
     * @link http://redis.io/commands/eval
     *
     * @param string $script
     * @param array|null $keys
     * @param array|null $args
     * @return mixed
     */
    public function evalScript($script, $keys = null, $args = null) {
        $params = [
            Parameter::string($script)
        ];
        if (is_array($keys)) {
            $params[] = Parameter::integer(count($keys));
            $params[] = Parameter::keys($keys);
        } else {
            $params[] = Parameter::integer(0);
        }
        if (is_array($args)) {
            $params[] = Parameter::strings($args);
        }
        return $this->returnCommand(['EVAL'], $params);
    }

    /**
     * EVALSHA sha1 numkeys key [key ...] arg [arg ...]
     * Available since 2.6.0.
     * Time complexity: Depends on the script that is executed.
     * @link http://redis.io/commands/evalsha
     *
     * @param string $sha
     * @param array|null $keys
     * @param array|null $args
     * @return mixed
     */
    public function evalsha($sha, $keys = null, $args = null) {
        $params = [
            Parameter::string($sha)
        ];
        if (is_array($keys)) {
            $params[] = Parameter::integer(count($keys));
            $params[] = Parameter::keys($keys);
        } else {
            $params[] = Parameter::integer(0);
        }
        if (is_array($args)) {
            $params[] = Parameter::strings($args);
        }
        return $this->returnCommand(['EVALSHA'], $params);
    }

    /**
     * SCRIPT EXISTS script [script ...]
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the number of scripts to check
     * (so checking a single script is an O(1) operation).
     * @link http://redis.io/commands/script-exists
     *
     * @param string|string[] $scriptsSha
     * @return int|int[]
     */
    public function scriptExists($scriptsSha) {
        return $this->returnCommand(['SCRIPT', 'EXISTS'], Parameter::strings($scriptsSha));
    }

    /**
     * SCRIPT FLUSH
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the number of scripts in cache.
     * @link http://redis.io/commands/script-flush
     *
     * @return bool True
     */
    public function scriptFlush() {
        return $this->returnCommand(['SCRIPT', 'FLUSH']);
    }

    /**
     * SCRIPT KILL
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/script-kill
     *
     * @return bool
     */
    public function scriptKill() {
        return $this->returnCommand(['SCRIPT', 'KILL']);
    }

    /**
     * SCRIPT LOAD script
     * Available since 2.6.0.
     * Time complexity: O(N) with N being the length in bytes of the script body.
     * @link http://redis.io/commands/script-load
     *
     * @param string $script
     * @return string
     */
    public function scriptLoad($script) {
        return $this->returnCommand(['SCRIPT', 'LOAD'], [Parameter::string($script)]);
    }

}
