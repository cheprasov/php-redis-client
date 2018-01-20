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
namespace RedisClient\Command\Traits;

trait AbstractCommandsTrait {

    /**
     * @param array $command
     * @param null|string|string[] $keys
     * @param array|null $params
     * @param int|null $parserId
     */
    abstract protected function returnCommand(array $command, $keys = null, array $params = null, $parserId = null);

    /**
     * @param array $subCommand
     * @param array $unsubCommand
     * @param array|null $params
     * @param \Closure|string|array $callback
     * @return mixed
     */
    abstract protected function subscribeCommand(array $subCommand, array $unsubCommand, array $params = null, $callback);

    /**
     * @return string
     */
    abstract public function getSupportedVersion();

    /**
     * @var array
     */
    protected static $methodForReservedWord = [
        'eval' => 'evalScript',
        'echo' => 'echoMessage',
    ];

    /**
     * @param string $name
     * @return string|null
     */
    protected function getMethodNameForReservedWord($name) {
        if (isset(static::$methodForReservedWord[$name])) {
            return static::$methodForReservedWord[$name];
        }
        return null;
    }
}
