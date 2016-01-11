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
use RedisClient\Command\Response\ResponseParser;

trait ServerCommandsTrait {

    /**
     * BGREWRITEAOF
     * Available since 1.0.0.
     *
     * @return bool Always true
     */
    public function bgrewriteaof() {
        return $this->returnCommand(['BGREWRITEAOF']);
    }

    /**
     * BGSAVE
     * Available since 1.0.0.
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
     *
     * @return string|null The connection name, or a null bulk reply if no name is set.
     */
    public function clientGetname() {
        // todo: check
        return $this->returnCommand(['CLIENT', 'GETNAME'], []);
    }

    /**
     * CLIENT KILL [ip:port] [ID client-id] [TYPE normal|slave|pubsub] [ADDR ip:port] [SKIPME yes/no]
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     *
     * @param string|array|null $addr
     * @param int|null $clientId
     * @param string|null $type
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
            $params[] = Parameter::string('ID');
            $params[] = Parameter::integer($clientId);
        }
        if ($type && preg_match('/^(normal|slave|pubsub)$/i', $type)) {
            $params[] = Parameter::string('TYPE');
            $params[] = Parameter::string(strtolower(trim($type)));
        }
        if ($addr2) {
            $params[] = Parameter::string('ADDR');
            $params[] = Parameter::address($addr2);
        }
        if (isset($skipme)) {
            $params[] = Parameter::string('SKIPME');
            $params[] = Parameter::address($skipme ? 'yes' : 'no');
        }
        return $this->returnCommand(['CLIENT', 'KILL'], $params);
    }

    /**
     * CLIENT LIST
     * Available since 2.4.0.
     * Time complexity: O(N) where N is the number of client connections
     *
     * @return string
     */
    public function clientList() {
        return $this->returnCommand(['CLIENT', 'LIST']);
    }

    /**
     * CLIENT SETNAME connection-name
     * Available since 2.6.9.
     * Time complexity: O(1)
     *
     * @param string $connectionName
     * @param bool True if the connection name was successfully set.
     */
    public function clientSetname($connectionName) {
        return $this->returnCommand(['CLIENT', 'SETNAME'], [Parameter::string($connectionName)]);
    }

    /**
     * CONFIG GET parameter
     * Available since 2.0.0.
     *
     * @param string|string[]
     * @return array
     */
    public function configGet($parameter) {
        return $this->returnCommand(['CONFIG', 'GET'], [Parameter::string($parameter)], ResponseParser::PARSE_ASSOC_ARRAY);
    }

    /**
     * CONFIG RESETSTAT
     * Available since 2.0.0.
     * Time complexity: O(1)
     *
     * @return bool always True
     */
    public function configResetstat() {
        return $this->returnCommand(['CONFIG', 'RESETSTAT']);
    }

    /**
     * CONFIG SET parameter value
     * Available since 2.0.0.
     *
     * @return bool True when the configuration was set properly. Otherwise an error is returned.
     */
    public function configSet($parameter, $value) {
        return $this->returnCommand(['CONFIG', 'SET']);
    }

    /**
     * DBSIZE
     * Available since 1.0.0.
     *
     * @return int The number of keys in the currently-selected database.
     */
    public function dbsize() {
        return $this->returnCommand(['DBSIZE']);
    }

    /**
     * DEBUG OBJECT key
     * Available since 1.0.0.
     *
     * @param string $key
     * @return string
     */
    public function debugObject($key) {
        return $this->returnCommand(['DEBUG', 'OBJECT'], [Parameter::key($key)]);
    }

    /**
     * DEBUG SEGFAULT
     * Available since 1.0.0.
     *
     * @return string
     */
    public function debugSegfault() {
        return $this->returnCommand(['DEBUG', 'SEGFAULT']);
    }

    /**
     * FLUSHALL
     * Available since 1.0.0.
     *
     * @return bool
     */
    public function flushall() {
        return $this->returnCommand(['FLUSHALL']);
    }

    /**
     * FLUSHDB
     * Available since 1.0.0.
     *
     * @return bool
     */
    public function flushdb() {
        return $this->returnCommand(['FLUSHDB']);
    }

    /**
     * INFO [section]
     * Available since 1.0.0.
     *
     * @param string $section
     * @return string
     */
    public function info($section = null) {
        return $this->returnCommand(['INFO'], $section ? [Parameter::string($section)] : null, ResponseParser::PARSE_INFO);
    }

    /**
     * LASTSAVE
     * Available since 1.0.0.
     *
     * @return int an UNIX time stamp.
     */
    public function lastsave() {
        return $this->returnCommand(['LASTSAVE']);
    }

    /**
     * MONITOR
     * Available since 1.0.0.
     */
    public function monitor() {
        return $this->returnCommand(['MONITOR']);
    }

    /**
     * SAVE
     * Available since 1.0.0.
     *
     * @return bool The commands returns True on success
     */
    public function save() {
        return $this->returnCommand(['SAVE']);
    }

    /**
     * SHUTDOWN [NOSAVE] [SAVE]
     * Available since 1.0.0.
     *
     * @param string|null $save NOSAVE or SAVE
     */
    public function shutdown($save) {
        return $this->returnCommand(['SHUTDOWN'], $save ? Parameter::enum($save, ['NOSAVE', 'SAVE']) : null);
    }

    /**
     * SLAVEOF host port
     * Available since 1.0.0.
     *
     * @param string $host
     * @param string $port
     * @return bool
     */
    public function slaveof($host, $port) {
        return $this->returnCommand(['SLAVEOF'], [
            Parameter::string($host),
            Parameter::port($port)
        ]);
    }

    /**
     * SLOWLOG subcommand [argument]
     * Available since 2.2.12.
     *
     * @param string $subcommand
     * @param string|null $argument
     * @return mixed
     */
    public function slowlog($subcommand, $argument = null) {
        $params = [
            Parameter::enum($subcommand, ['GET', 'LEN', 'RESET'])
        ];
        if (isset($argument)) {
            $params[] = Parameter::string($argument);
        }
        return $this->returnCommand(['SLOWLOG'], $params);
    }

    /**
     * SYNC
     * Available since 1.0.0.
     *
     */
    public function sync() {
        return $this->returnCommand(['SYNC']);
    }

    /**
     * TIME
     * Available since 2.6.0.
     * Time complexity: O(1)
     *
     * @return string
     */
    public function time() {
        return $this->returnCommand(['TIME'], null, ResponseParser::PARSE_TIME);
    }

}
