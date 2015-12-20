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
     * UNSUBSCRIBE [channel [channel ...]]
     * Available since 2.0.0.
     * Time complexity: O(N) where N is the number of clients already subscribed to a channel.
     *
     * @param string|string[]|null $channel
     * @return
     */
    public function unsubscribe($channel) {
        return $this->returnCommand(
            new Command('UNSUBSCRIBE', isset($channel) ? Parameter::strings($channel) : null)
        );
    }

}
