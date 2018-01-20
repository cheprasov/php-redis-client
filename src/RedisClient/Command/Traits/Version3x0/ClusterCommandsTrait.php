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
namespace RedisClient\Command\Traits\Version3x0;

/**
 * Cluster Commands
 * @link http://redis.io/commands#cluster
 * @link http://redis.io/topics/cluster-tutorial
 */
trait ClusterCommandsTrait {

    /**
     * CLUSTER ADDSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     * @link http://redis.io/commands/cluster-addslots
     *
     * @param int|int[] $slots
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterAddslots($slots) {
        return $this->returnCommand(['CLUSTER', 'ADDSLOTS'], null, (array)$slots);
    }

    /**
     * CLUSTER COUNT-FAILURE-REPORTS node-id
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of failure reports
     * @link http://redis.io/commands/cluster-count-failure-reports
     *
     * @param int $nodeId
     * @return int The number of active failure reports for the node.
     */
    public function clusterCountFailureReports($nodeId) {
        return $this->returnCommand(['CLUSTER', 'COUNT-FAILURE-REPORTS'], null, [$nodeId]);
    }

    /**
     * CLUSTER COUNTKEYSINSLOT slot
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-countkeysinslot
     *
     * @param int $slot
     * @return int
     */
    public function clusterCountkeysinslot($slot) {
        return $this->returnCommand(['CLUSTER', 'COUNTKEYSINSLOT'], null, [$slot]);
    }

    /**
     * CLUSTER DELSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     * @link http://redis.io/commands/cluster-delslots
     *
     * @param int|int[] $slots
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterDelslots($slots) {
        return $this->returnCommand(['CLUSTER', 'DELSLOTS'], null, (array)$slots);
    }

    /**
     * CLUSTER FAILOVER [FORCE|TAKEOVER]
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-failover
     *
     * @param string|null $option FORCE|TAKEOVER
     * @return
     */
    public function clusterFailover($option = null) {
        return $this->returnCommand(['CLUSTER', 'FAILOVER'], null, $option ? [$option] : null);
    }

    /**
     * CLUSTER FORGET node-id
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-forget
     *
     * @param int $nodeId
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterForget($nodeId) {
        return $this->returnCommand(['CLUSTER', 'FORGET'], null, [$nodeId]);
    }

    /**
     * CLUSTER GETKEYSINSLOT slot count
     * Available since 3.0.0.
     * Time complexity: O(log(N)) where N is the number of requested keys
     * @link http://redis.io/commands/cluster-getkeysinslot
     *
     * @param int $slot
     * @param int $count
     * @return array From 0 to count key names in a Redis array reply.
     */
    public function clusterGetkeysinslot($slot, $count) {
        return $this->returnCommand(['CLUSTER', 'GETKEYSINSLOT'], null, [$slot, $count]);
    }

    /**
     * CLUSTER INFO
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-info
     *
     * @return string A map between named fields and values in the form of <field>:<value>
     * lines separated by newlines composed by the two bytes CRLF.
     */
    public function clusterInfo() {
        return $this->returnCommand(['CLUSTER', 'INFO']);
    }

    /**
     * CLUSTER KEYSLOT key
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of bytes in the key
     * @link http://redis.io/commands/cluster-keyslot
     *
     * @param string $key
     * @return int The hash slot number.
     */
    public function clusterKeyslot($key) {
        return $this->returnCommand(['CLUSTER', 'KEYSLOT'], $key, [$key]);
    }

    /**
     * CLUSTER MEET ip port
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-meet
     *
     * @param string $ip
     * @param int $port
     * @return bool True if the command was successful.
     * If the address or port specified are invalid an error is returned.
     */
    public function clusterMeet($ip, $port) {
        return $this->returnCommand(['CLUSTER', 'MEET'], null, [$ip, $port]);
    }

    /**
     * CLUSTER NODES
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
     * @link http://redis.io/commands/cluster-nodes
     *
     * @return string The serialized cluster configuration.
     */
    public function clusterNodes() {
        return $this->returnCommand(['CLUSTER', 'NODES']);
    }

    /**
     * CLUSTER REPLICATE node-id
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-replicate
     *
     * @param int $nodeId
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterReplicate($nodeId) {
        return $this->returnCommand(['CLUSTER', 'REPLICATE'], null, [$nodeId]);
    }

    /**
     * CLUSTER RESET [HARD|SOFT]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of known nodes.
     * The command may execute a FLUSHALL as a side effect.
     * @link http://redis.io/commands/cluster-reset
     *
     * @param string $option HARD|SOFT
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterReset($option = null) {
        return $this->returnCommand(['CLUSTER', 'RESET'], null, $option ? [$option] : null);
    }

    /**
     * CLUSTER SAVECONFIG
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-saveconfig
     *
     * @return bool
     */
    public function clusterSaveconfig() {
        return $this->returnCommand(['CLUSTER', 'SAVECONFIG']);
    }

    /**
     * CLUSTER SET-CONFIG-EPOCH config-epoch
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-set-config-epoch
     *
     * @param string $config
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterSetConfigEpoch($config) {
        return $this->returnCommand(['CLUSTER', 'SET-CONFIG-EPOCH'], null, [$config]);
    }

    /**
     * CLUSTER SETSLOT slot IMPORTING|MIGRATING|STABLE|NODE [node-id]
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-setslot
     *
     * @param string $slot
     * @param string $subcommand IMPORTING|MIGRATING|STABLE|NODE
     * @param int|null $nodeId
     * @return bool All the subcommands return OK if the command was successful. Otherwise an error is returned.
     */
    public function clusterSetslot($slot, $subcommand, $nodeId = null) {
        $params = [$slot, $subcommand];
        if (isset($nodeId)) {
            $params[] = $nodeId;
        }
        return $this->returnCommand(['CLUSTER', 'SETSLOT'], null, $params);
    }

    /**
     * CLUSTER SLAVES node-id
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/cluster-slaves
     *
     * @param int $nodeId
     * @return string The serialized cluster configuration.
     */
    public function clusterSlaves($nodeId) {
        return $this->returnCommand(['CLUSTER', 'SLAVES'], null, [$nodeId]);
    }

    /**
     * CLUSTER SLOTS
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
     * @link http://redis.io/commands/cluster-slots
     *
     * @return array Nested list of slot ranges with IP/Port mappings.
     */
    public function clusterSlots() {
        return $this->returnCommand(['CLUSTER', 'SLOTS']);
    }

    /**
     * READONLY
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/readonly
     *
     * @return bool
     */
    public function readonly() {
        return $this->returnCommand(['READONLY']);
    }

    /**
     * READWRITE
     * Available since 3.0.0.
     * Time complexity: O(1)
     * @link http://redis.io/commands/readwrite
     *
     * @return bool
     */
    public function readwrite() {
        return $this->returnCommand(['READWRITE']);
    }

}
