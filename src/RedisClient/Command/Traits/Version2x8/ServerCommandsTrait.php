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
namespace RedisClient\Command\Traits\Version2x8;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Traits\Version2x6\ServerCommandsTrait as ServerCommandsTraitVersion26;

trait ServerCommandsTrait {

    use ServerCommandsTraitVersion26;

    /**
     * COMMAND
     * Available since 2.8.13.
     * Time complexity: O(N) where N is the total number of Redis commands
     * @link http://redis.io/commands/command
     *
     * @return array
     */
    public function command() {
        return $this->returnCommand(['COMMAND']);
    }

    /**
     * COMMAND COUNT
     * Available since 2.8.13.
     * Time complexity: O(1)
     *
     * @return int Number of commands returned by COMMAND
     */
    public function commandCount() {
        // todo: check
        return $this->returnCommand(['COMMAND', 'COUNT'], []);
    }

    /**
     * COMMAND GETKEYS command
     * Available since 2.8.13.
     * Time complexity: O(N) where N is the number of arguments to the command
     *
     * @param string $command
     * @return string[] List of keys from your command.
     */
    public function commandGetkeys($command) {
        return $this->returnCommand(['COMMAND', 'GETKEYS'], [Parameter::command($command)]);
    }

    /**
     * COMMAND INFO command-name [command-name ...]
     * Available since 2.8.13.
     * Time complexity: O(N) when N is number of commands to look up
     *
     * @param string[] $commandNames
     * @return array Nested list of command details.
     */
    public function commandInfo($commandNames) {
        return $this->returnCommand(['COMMAND', 'INFO'], (array) $commandNames);
    }

    /**
     * CONFIG REWRITE
     * Available since 2.8.0.
     *
     * @return bool True when the configuration was rewritten properly. Otherwise an error is returned.
     */
    public function configRewrite() {
        return $this->returnCommand(['CONFIG', 'REWRITE']);
    }

    /**
     * ROLE
     * Available since 2.8.12.
     *
     * @return array
     */
    public function role() {
        return $this->returnCommand(['ROLE']);
    }

}
