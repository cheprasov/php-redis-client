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

/**
 * Cluster
 */

namespace Examples;

require (dirname(__DIR__).'/vendor/autoload.php');
// or require (dirname(__DIR__).'/src/autoloader.php');

use RedisClient\ClientFactory;
use RedisClient\Cluster\ClusterMap;

// Example 1. Create RedisClient with Cluster support;
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:7001', // Default server for connection
    'cluster' => [
        'enabled' => true
    ]
]);
print_r($RedisClient->clusterInfo());
/*
cluster_state:ok
cluster_slots_assigned:16384
cluster_slots_ok:16384
cluster_slots_pfail:0
cluster_slots_fail:0
cluster_known_nodes:6
cluster_size:3
cluster_current_epoch:6
cluster_my_epoch:1
cluster_stats_messages_sent:472694
cluster_stats_messages_received:472694
 */


// Example 2. Create RedisClient with Cluster Slot Map;
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:7001', // Default server for connection
    'cluster' => [
        'enabled' => true,
        'clusters' => [
            5460  => '127.0.0.1:7001', // slots from 0 to 5460
            10922 => '127.0.0.1:7002', // slots from 5461 to 10922
            16383 => '127.0.0.1:7003', // slots from 10923 to 16383
        ],
    ]
]);


// Example 3. Get Cluster Slot Map from Redis Server on init RedisClient
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:7001', // Default server for connection
    'cluster' => [
        'enabled' => true,
        // Use the param to update cluster slot map below on init RedisClient.
        // RedisClient will execute command CLUSTER SLOTS to get map.
        'init_on_start' => true,
    ]
]);


// Example 4. Get Cluster Slot Map from Redis Server by command
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:7001', // Default server for connection
    'cluster' => [
        'enabled' => true,
    ]
]);
// Use this command to sync Cluster Slot Map from Redis Server
$RedisClient->_syncClusterSlotsFromRedisServer();


// Example 5. Pseudo Redis Cluster.
// If you use several Redis Servers without Cluster,
// you can use RedisClient's cluster config just for sharding keys by the Cluster rulers.
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:6381', // Default server for connection
    'cluster' => [
        'enabled' => true,
        'clusters' => [
            5460  => '127.0.0.1:6381', // slots from 0 to 5460
            10922 => '127.0.0.1:6383', // slots from 5461 to 10922
            16383 => '127.0.0.1:6385', // slots from 10923 to 16383
        ],
    ]
]);
$RedisClient->set('foo', 'foo-42');
echo ClusterMap::getSlotByKey('foo') . PHP_EOL; // 12182

$RedisClient->set('bar', 'bar-42');
echo ClusterMap::getSlotByKey('bar') . PHP_EOL; // 5061

echo $RedisClient->get('foo') . PHP_EOL; // foo-42
echo $RedisClient->get('bar') . PHP_EOL; // bar-42

// But, be careful with multi operation for pseudo Clusters,
print_r($RedisClient->mget(['foo', 'bar'])); // ['foo-42', null]
print_r($RedisClient->mget(['bar', 'foo'])); // ['bar-42', null]


// Example 6. Each connection to Redis Server uses the same config.
// For example, use can use password for all servers
$RedisClient = ClientFactory::create([
    'server' => '127.0.0.1:6382', // Default server for connection
    'timeout' => 2,
    'password' => 'test-password-123',
    'cluster' => [
        'enabled' => true,
        'clusters' => [
            5460  => '127.0.0.1:6382', // slots from 0 to 5460
            10922 => '127.0.0.1:6384', // slots from 5461 to 10922
            16383 => '127.0.0.1:6386', // slots from 10923 to 16383
        ],
    ]
]);
$RedisClient->set('foo', 'foo-43');
echo ClusterMap::getSlotByKey('foo') . PHP_EOL; // 12182

$RedisClient->set('bar', 'bar-43');
echo ClusterMap::getSlotByKey('bar') . PHP_EOL; // 5061

echo $RedisClient->get('foo') . PHP_EOL; // foo-42
echo $RedisClient->get('bar') . PHP_EOL; // bar-42


