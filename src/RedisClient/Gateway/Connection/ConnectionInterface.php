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
namespace RedisClient\Connection;

interface ConnectionInterface {

    /**
     * @return string
     */
    public function getServer();

    /**
     * @param callable $callback
     */
    public function onConnect($callback);

    /**
     * @param string $string
     * @return int|null
     */
    public function write($string);

    /**
     * @return string|null
     */
    public function readLine();

    /**
     * @param int $length
     * @return string|null
     */
    public function read($length);

}
