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
namespace RedisClient\Command\Traits\Version2x6;

use RedisClient\Command\Parameter\Parameter;
use RedisClient\Command\Response\ResponseParser;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    /**
     * BGREWRITEAOF
     * Available since 1.0.0.
     * @link http://redis.io/commands/bgrewriteaof
     *
     * @return bool|string Always true
     */
    public function bgrewriteaof() {
        return $this->returnCommand(['BGREWRITEAOF']);
    }

    /**
     * BGSAVE
     * Available since 1.0.0.
     * @link http://redis.io/commands/bgsave
     *
     * @return string
     */
    public function bgsave() {
        return $this->returnCommand(['BGSAVE']);
    }

    /**
     * CLIENT GETNAME
     * Available since 2.6.9.
     * Time complexity: O(1)
     * @link http://redis.io/commands/client-getname
     *
     * @return string|null The connection name, or a null bulk reply if no name is set.
     */
    public function clientGetname() {
        return $this->returnCommand(['CLIENT', 'GETNAME']);
    }

    /**
     * CLIENT KILL [ip:port] [ID client-id] [TYPE normal|slave|pubsub] [ADDR ip:port] [SKIPME yes/no]
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     * @link http://redis.io/commands/client-kill
     *
     * @param string|array|null $addr
     * @param int|null $clientId
     * @param string|null $type normal|slave|pubsub
     * @param string|array|null $addr2
     * @param bool|null $skipme
     * @return bool|int
     * When called with the three arguments format:
     * Simple string reply: True if the connection exists and has been closed
     * When called with the filter / value format:
     * Integer reply: the number of clients killed.
     */
    public function clientKill($addr = null, $clientId = null, $type = null, $addr2 = null, $skipme = null) {
        $params = [];
        if ($addr) {
            $params[] = Parameter::address($addr);
        }
        if ($clientId) {
            $params[] = 'ID';
            $params[] = $clientId;
        }
        if ($type) {
            $params[] = 'TYPE';
            $params[] = $type;
        }
        if ($addr2) {
            $params[] = 'ADDR';
            $params[] = Parameter::address($addr2);
        }
        if (isset($skipme)) {
            $params[] = 'SKIPME';
            $params[] = $skipme ? 'yes' : 'no';
        }
        return $this->returnCommand(['CLIENT', 'KILL'], null, $params);
    }

    /**
     * CLIENT LIST
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     * @link http://redis.io/commands/client-list
     *
     * @return string
     */
    public function clientList() {
        return $this->returnCommand(['CLIENT', 'LIST'], null, null, ResponseParser::PARSE_CLIENT_LIST);
    }

    /**
     * CLIENT SETNAME connection-name
     * Available since 2.6.9.
     * Time complexity: O(1)
     * @link http://redis.io/commands/client-setname
     *
     * @param string $connectionName
     * @param bool True if the connection name was successfully set.
     */
    public function clientSetname($connectionName) {
        return $this->returnCommand(['CLIENT', 'SETNAME'], null, [$connectionName]);
    }

    /**
     * CONFIG GET parameter
     * Available since 2.0.0.
     * @link http://redis.io/commands/config-get
     *
     * @param string|string[]
     * @return array
     */
    public function configGet($parameter) {
        return $this->returnCommand(['CONFIG', 'GET'], null, [$parameter], ResponseParser::PARSE_ASSOC_ARRAY);
    }

    /**
     * CONFIG RESETSTAT
     * Available since 2.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/config-resetstat
     *
     * @return bool always True
     */
    public function configResetstat() {
        return $this->returnCommand(['CONFIG', 'RESETSTAT']);
    }

    /**
     * CONFIG SET parameter value
     * Available since 2.0.0.
     * @link http://redis.io/commands/config-set
     *
     * @param string $parameter
     * @param string $value
     * @return bool True when the configuration was set properly. Otherwise an error is returned.
     */
    public function configSet($parameter, $value) {
        return $this->returnCommand(['CONFIG', 'SET'], null, [$parameter, $value]);
    }

    /**
     * DBSIZE
     * Available since 1.0.0.
     * @link http://redis.io/commands/dbsize
     *
     * @return int The number of keys in the currently-selected database.
     */
    public function dbsize() {
        return $this->returnCommand(['DBSIZE']);
    }

    /**
     * DEBUG OBJECT key
     * Available since 1.0.0.
     * @link http://redis.io/commands/debug-object
     *
     * @param string $key
     * @return string
     */
    public function debugObject($key) {
        return $this->returnCommand(['DEBUG', 'OBJECT'], $key, [$key]);
    }

    /**
     * DEBUG SEGFAULT
     * Available since 1.0.0.
     * @link http://redis.io/commands/debug-segfault
     *
     * @return string
     */
    public function debugSegfault() {
        return $this->returnCommand(['DEBUG', 'SEGFAULT']);
    }

    /**
     * FLUSHALL
     * Available since 1.0.0.
     * @link http://redis.io/commands/flushall
     *
     * @return bool
     */
    public function flushall() {
        return $this->returnCommand(['FLUSHALL']);
    }

    /**
     * FLUSHDB
     * Available since 1.0.0.
     * @link http://redis.io/commands/flushdb
     *
     * @return bool
     */
    public function flushdb() {
        return $this->returnCommand(['FLUSHDB']);
    }

    /**
     * INFO [section]
     * Available since 1.0.0.
     * @link http://redis.io/commands/info
     *
     * @param string $section
     * @return array
     */
    public function info($section = null) {
        return $this->returnCommand(['INFO'], null, $section ? [$section] : null, ResponseParser::PARSE_INFO);
    }

    /**
     * LASTSAVE
     * Available since 1.0.0.
     * @link http://redis.io/commands/lastsave
     *
     * @return int an UNIX time stamp.
     */
    public function lastsave() {
        return $this->returnCommand(['LASTSAVE']);
    }

    /**
     * MONITOR
     * Available since 1.0.0.
     * @link http://redis.io/commands/monitor
     *
     * @param \Closure $callback
     * @return mixed
     */
    public function monitor(\Closure $callback) {
        return $this->subscribeCommand(['MONITOR'], ['QUIT'], null, $callback);
    }

    /**
     * SAVE
     * Available since 1.0.0.
     * @link http://redis.io/commands/save
     *
     * @return bool The commands returns True on success
     */
    public function save() {
        return $this->returnCommand(['SAVE']);
    }

    /**
     * SHUTDOWN [NOSAVE|SAVE]
     * Available since 1.0.0.
     * @link http://redis.io/commands/shutdown
     *
     * @param string|null $save NOSAVE or SAVE
     */
    public function shutdown($save) {
        return $this->returnCommand(['SHUTDOWN'], null, $save ? [$save] : null);
    }

    /**
     * SLAVEOF host port
     * Available since 1.0.0.
     * @link http://redis.io/commands/slaveof
     *
     * @param string $host
     * @param string $port
     * @return bool
     */
    public function slaveof($host, $port) {
        return $this->returnCommand(['SLAVEOF'], null, [$host, $port]);
    }

    /**
     * SLOWLOG subcommand [argument]
     * Available since 2.2.12.
     * @link http://redis.io/commands/slowlog
     *
     * @param string $subcommand GET|LEN|RESET
     * @param string|null $argument
     * @return mixed
     */
    public function slowlog($subcommand, $argument = null) {
        $params = [$subcommand];
        if (isset($argument)) {
            $params[] = $argument;
        }
        return $this->returnCommand(['SLOWLOG'], null, $params);
    }

    /**
     * SYNC
     * Available since 1.0.0.
     * @link http://redis.io/commands/sync
     */
    public function sync() {
        return $this->returnCommand(['SYNC']);
    }

    /**
     * TIME
     * Available since 2.6.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/time
     *
     * @return string
     */
    public function time() {
        return $this->returnCommand(['TIME'], null, null, ResponseParser::PARSE_TIME);
    }

}
