<?php

namespace RedisClient\Command\Traits;

use RedisClient\Command\Command;
use RedisClient\Command\Parameter\Parameter;

/**
 * Cluster
 * @link http://redis.io/commands#pubsub
 */
trait ClusterCommandsTrait {

    /**
     * CLUSTER ADDSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     *
     * @param int|int[] $slot
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterAddslots($slot) {
        return $this->returnCommand(
            new Command('CLUSTER ADDSLOTS', Parameter::integers($slot))
        );
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
        return $this->returnCommand(
            new Command('PUBLISH', Parameter::integers($nodeId))
        );
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
        return $this->returnCommand(
            new Command('CLUSTER COUNTKEYSINSLOT', Parameter::integer($slot))
        );
    }

    /**
     * CLUSTER DELSLOTS slot [slot ...]
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of hash slot arguments
     *
     * @param int|int[] $slot
     * @return bool True if the command was successful. Otherwise an error is returned.
     */
    public function clusterDelslots($slot) {
        return $this->returnCommand(
            new Command('CLUSTER DELSLOTS', Parameter::integers($slot))
        );
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
        return $this->returnCommand(
            new Command(
                'CLUSTER FAILOVER',
                $option ? Parameter::enum($option, ['FORCE', 'TAKEOVER']) : null
            )
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
        return $this->returnCommand(
            new Command('CLUSTER FORGET', Parameter::integer($nodeId))
        );
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
        return $this->returnCommand(
            new Command('CLUSTER GETKEYSINSLOT', [
                Parameter::integer($slot),
                Parameter::integer($count)
            ])
        );
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
        return $this->returnCommand(
            new Command('CLUSTER INFO', [
                Parameter::integer($slot),
                Parameter::integer($count)
            ])
        );
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
        return $this->returnCommand(
            new Command('CLUSTER KEYSLOT', Parameter::string($key))
        );
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
        return $this->returnCommand(
            new Command('CLUSTER MEET', [
                Parameter::string($ip),
                Parameter::integer($port)
            ])
        );
    }

    /**
     * CLUSTER NODES
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
     *
     * @return string The serialized cluster configuration.
     */
    public function clusterNodes() {
        return $this->returnCommand(
            new Command('CLUSTER NODES')
        );
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
        return $this->returnCommand(
            new Command('CLUSTER REPLICATE', Parameter::integer($nodeId))
        );
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
        return $this->returnCommand(
            new Command('CLUSTER RESET', $option ? Parameter::enum($option, ['HARD', 'SOFT']) : null)
        );
    }

    /**
     * CLUSTER SAVECONFIG
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @return bool
     */
    public function clusterSaveconfig() {
        return $this->returnCommand(
            new Command('CLUSTER SAVECONFIG')
        );
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
        return $this->returnCommand(
            new Command('CLUSTER SET-CONFIG-EPOCH', Parameter::string($config))
        );
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
        return $this->returnCommand(
            new Command('CLUSTER SETSLOT', $params)
        );
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
        return $this->returnCommand(
            new Command('CLUSTER SLAVES', Parameter::integer($nodeId))
        );
    }

    /**
     * CLUSTER SLOTS
     * Available since 3.0.0.
     * Time complexity: O(N) where N is the total number of Cluster nodes
     *
     * @return array Nested list of slot ranges with IP/Port mappings.
     */
    public function clusterSlots() {
        return $this->returnCommand(
            new Command('CLUSTER SLOTS')
        );
    }

    /**
     * READONLY
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @return bool
     */
    public function readonly() {
        return $this->returnCommand(
            new Command('READONLY')
        );
    }

    /**
     * READWRITE
     * Available since 3.0.0.
     * Time complexity: O(1)
     *
     * @return bool
     */
    public function readwrite() {
        return $this->returnCommand(
            new Command('READWRITE')
        );
    }

}
