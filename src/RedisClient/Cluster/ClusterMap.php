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
namespace RedisClient\Cluster;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Cluster\Hash\Crc16;
use RedisClient\Protocol\ProtocolFactory;
use RedisClient\Protocol\ProtocolInterface;

class ClusterMap {

    const MAX_SLOT = 16384;

    /**
     * @var array
     */
    protected $clusters = [];

    /**
     * @var ProtocolInterface[]
     */
    protected $protocols = [];

    /**
     * @var AbstractRedisClient;
     */
    protected $RedisClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param AbstractRedisClient $RedisClient
     * @param array $config
     */
    public function __construct(AbstractRedisClient $RedisClient, array $config) {
        $this->RedisClient = $RedisClient;
        $this->config = $config;
    }

    /**
     * @param array $clusters
     */
    public function setClusters(array $clusters) {
        ksort($clusters, SORT_NUMERIC);
        $this->clusters = $clusters;
    }

    /**
     * @return array
     */
    public function getClusters() {
        return $this->clusters;
    }

    /**
     * @param int $slot
     * @param string $server
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
        return Crc16::hash($key) % self::MAX_SLOT;
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
        return isset($server) ? $server : null;
    }

    /**
     * @param string $server
     * @return ProtocolInterface
     */
    public function getProtocolByServer($server) {
        if (!isset($this->protocols[$server])) {
            $config = $this->config;
            $config[AbstractRedisClient::CONFIG_SERVER] = $server;
            $this->protocols[$server] = ProtocolFactory::createRedisProtocol($this->RedisClient, $config);
        }
        return $this->protocols[$server];
    }

    /**
     * @param ProtocolInterface $Protocol
     */
    public function addProtocol(ProtocolInterface $Protocol) {
        $server = $Protocol->getConnection()->getServer();
        if (0 === strpos($server, 'tcp://')) {
            $server = substr($server, 6);
        }
        $this->protocols[$server] = $Protocol;
    }

    /**
     * @param $key
     * @return ProtocolInterface|null
     */
    public function getProtocolByKey($key) {
        if (!$server = $this->getServerBySlot(self::getSlotByKey($key))) {
            return null;
        }
        return $this->getProtocolByServer($server);
    }
}
