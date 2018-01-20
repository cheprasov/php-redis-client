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
namespace RedisClient\Protocol;

use RedisClient\Connection\ConnectionInterface;

interface ProtocolInterface {

    /**
     * @param ConnectionInterface $Connection
     */
    public function setConnection(ConnectionInterface $Connection);

    /**
     * @return ConnectionInterface $Connection
     */
    public function getConnection();

    /**
     * @param string $command
     * @return mixed
     */
    public function sendRaw($command);

    /**
     * @param string[] $structure
     * @return mixed
     */
    public function send(array $structure);

    /**
     * @param array[] $structures
     * @return mixed
     */
    public function sendMulti(array $structures);

    /**
     * @param string[] $structure
     * @param \Closure|string|array $callback
     * @return mixed
     */
    public function subscribe(array $structure, $callback);

}
