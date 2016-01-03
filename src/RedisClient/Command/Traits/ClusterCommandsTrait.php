<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Parameter\Parameter;

/**
 * trait ClusterCommandsTrait
 * @link http://redis.io/commands#pubsub
 */
trait ClusterCommandsTrait {

    /**
     * CLUSTER ADDSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     *
     * @param int|int[] $slots
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterAddslots($slots) {
        return $this->returnCommand(['CLUSTER', 'ADDSLOTS'], Parameter::integers($slots));
    }

    /**
     * CLUSTER COUNT-FAILURE-REPORTS node-id
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of failure reports
     *
     * @param int $nodeId
     * @return int The number of active failure reports for the node.
     */
    public function clusterCountFailureReports($nodeId) {
        return $this->returnCommand(['PUBLISH'], [Parameter::integer($nodeId)]);
    }

    /**
     * CLUSTER COUNTKEYSINSLOT slot
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param int $slot
     * @return int
     */
    public function clusterCountkeysinslot($slot) {
        return $this->returnCommand(['CLUSTER', 'COUNTKEYSINSLOT'], [Parameter::integer($slot)]);
    }

    /**
     * CLUSTER DELSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     *
     * @param int|int[] $slots
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterDelslots($slots) {
        return $this->returnCommand(['CLUSTER', 'DELSLOTS'], Parameter::integers($slots));
    }

    /**
     * CLUSTER FAILOVER [FORCE|TAKEOVER]
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param string|null $option
     * @return
     */
    public function clusterFailover($option = null) {
        return $this->returnCommand(['CLUSTER', 'FAILOVER'],
            $option ? [Parameter::enum($option, ['FORCE', 'TAKEOVER'])] : null
        );
    }

    /**
     * CLUSTER FORGET node-id
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param int $nodeId
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterForget($nodeId) {
        return $this->returnCommand(['CLUSTER', 'FORGET'], [Parameter::integer($nodeId)]);
    }

    /**
     * CLUSTER GETKEYSINSLOT slot count
     * Available since 3.0.0.
     * Time complexity: O(log(N)) where N is the number of requested keys
     *
     * @param int $slot
     * @param int $count
     * @return array From 0 to count key names in a Redis array reply.
     */
    public function clusterGetkeysinslot($slot, $count) {
        return $this->returnCommand(['CLUSTER', 'GETKEYSINSLOT'], [
            Parameter::integer($slot),
            Parameter::integer($count)
        ]);
    }

    /**
     * CLUSTER INFO
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @return string A map between named fields and values in the form of <field>:<value>
     * lines separated by newlines composed by the two bytes CRLF.
     */
    public function clusterInfo($slot, $count) {
        return $this->returnCommand(['CLUSTER', 'INFO'], [
            Parameter::integer($slot),
            Parameter::integer($count)
        ]);
    }

    /**
     * CLUSTER KEYSLOT key
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of bytes in the key
     *
     * @param string $key
     * @return int The hash slot number.
     */
    public function clusterKeyslot($key) {
        return $this->returnCommand(['CLUSTER', 'KEYSLOT'], [Parameter::string($key)]);
    }

    /**
     * CLUSTER MEET ip port
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param string $ip
     * @param int $port
     * @return bool True if the command was successful.
     * If the address or port specified are invalid an error is returned.
     */
    public function clusterMeet($ip, $port) {
        return $this->returnCommand(['CLUSTER', 'MEET'], [
            Parameter::string($ip),
            Parameter::port($port)
        ]);
    }

    /**
     * CLUSTER NODES
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
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
     *
     * @param int $nodeId
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterReplicate($nodeId) {
        return $this->returnCommand(['CLUSTER', 'REPLICATE'], [Parameter::integer($nodeId)]);
    }

    /**
     * CLUSTER RESET [HARD|SOFT]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the number of known nodes. The command may execute a FLUSHALL as a side effect.
     *
     * @param string $option
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterReset($option = null) {
        return $this->returnCommand(['CLUSTER', 'RESET'], $option ? [Parameter::enum($option, ['HARD', 'SOFT'])] : null);
    }

    /**
     * CLUSTER SAVECONFIG
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @return bool
     */
    public function clusterSaveconfig() {
        return $this->returnCommand(['CLUSTER SAVECONFIG']);
    }

    /**
     * CLUSTER SET-CONFIG-EPOCH config-epoch
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param string $config
     * @return bool True if the command was executed successfully, otherwise an error is returned.
     */
    public function clusterSetConfigEpoch($config) {
        return $this->returnCommand(['CLUSTER', 'SET-CONFIG-EPOCH'], [Parameter::string($config)]);
    }

    /**
     * CLUSTER SETSLOT slot IMPORTING|MIGRATING|STABLE|NODE [node-id]
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param string $slot
     * @param string $subcommand
     * @param int|null $nodeId
     * @return bool All the subcommands return OK if the command was successful. Otherwise an error is returned.
     */
    public function clusterSetslot($slot, $subcommand, $nodeId = null) {
        $params = [
            Parameter::string($slot),
            Parameter::enum($subcommand, ['IMPORTING', 'MIGRATING', 'STABLE', 'NODE']),
        ];
        if ($nodeId) {
            $params[] = Parameter::integer($nodeId);
        }
        return $this->returnCommand(['CLUSTER', 'SETSLOT'], $params);
    }

    /**
     * CLUSTER SLAVES node-id
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @param int $nodeId
     * @return string The serialized cluster configuration.
     */
    public function clusterSlaves($nodeId) {
        return $this->returnCommand(['CLUSTER', 'SLAVES'], [Parameter::integer($nodeId)]);
    }

    /**
     * CLUSTER SLOTS
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
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
     *
     * @return bool
     */
    public function readwrite() {
        return $this->returnCommand(['READWRITE']);
    }

}
