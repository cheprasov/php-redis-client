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
namespace RedisClient\Cluster;

use RedisClient\Cluster\Hash\Crc16;
use RedisClient\Connection\ConnectionFactory;
use RedisClient\Connection\ConnectionInterface;

class ClusterMap {

    /**
     * @var array
     */
    protected $clusters = [];

    /**
     * @var ConnectionInterface[]
     */
    protected $connections = [];

    /**
     * @var int
     */
    protected $currentNum = 0;

    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * @param array|null $clusters
     * @param int $timeout
     */
    public function __construct(array $clusters = null, $timeout = 0) {
        if ($clusters) {
            $this->setClusters($clusters);
        }
        $this->timeout = $timeout;
    }

    /**
     * @param array $clusters
     */
    public function setClusters(array $clusters) {
        ksort($clusters, SORT_NUMERIC);
        $this->clusters = $clusters;
    }

    /**
     * @param int $slot
     * @param int $server
     */
    public function addCluster($slot, $server) {
        if (false !== ($oldSlot = array_search($server, $this->clusters))) {
            if ($oldSlot < $slot) {
                unset($this->clusters[$oldSlot]);
            } else {
                return;
            }
        }
        $this->clusters[$slot] = $server;
        ksort($this->clusters, SORT_NUMERIC);
    }

    /**
     * @param string $key
     * @return int
     */
    public static function getSlotByKey($key) {
        if (false !== ($s = strpos($key, '{'))) {
            if (false !== ($e = strpos($key, '}', $s)) && ($e - $s > 1)) {
                ++$s;
                $key = substr($key, $s, $e - $s);
            }
        }
        return Crc16::hash($key) & 16383;
    }

    /**
     * @param int $slot
     * @return string|null
     */
    public function getServerBySlot($slot) {
        foreach ($this->clusters as $maxSlot => $server) {
            if ($slot <= $maxSlot) {
                return $server;
            }
        }
        return null;
    }

    /**
     * @param string $server
     * @return ConnectionInterface
     */
    public function getConnectionByServer($server) {
        if (!isset($this->connections[$server])) {
            $this->connections[$server] = ConnectionFactory::createStreamConnection($server, $this->timeout);
        }
        return $this->connections[$server];
    }

    /**
     * @param $key
     * @return ConnectionInterface|null
     */
    public function getConnectionByKey($key) {
        if (!$server = $this->getServerBySlot(self::getSlotByKey($key))) {
            return null;
        }
        return $this->getConnectionByServer($server);
    }
}
