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
namespace Test\Integration\Version3x0;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Cluster\ClusterMap;
use RedisClient\Exception\CrossSlotResponseException;
use RedisClient\Exception\MovedResponseException;
use RedisClient\Pipeline\Pipeline;

include_once(__DIR__ . '/../ClusterVersionTest.php');

class ClusterTest extends \Test\Integration\ClusterVersionTest {

    /**
     * @return AbstractRedisClient|\RedisClient\Client\Version\RedisClient3x0|\RedisClient\Client\Version\RedisClient3x2|\RedisClient\Client\Version\RedisClient4x0
     */
    protected function getRedisClient() {
        $servers = $this->getServers();
        return $this->createRedisClient([
            'server' => $servers[0],
            'cluster' => [
                'enabled' => true,
                'init_on_start' => true,
            ]
        ]);
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::_syncClusterSlotsFromRedisServer
     */
    public function test_syncClusterSlotsFromRedisServer() {
        $servers = $this->getServers();
        $Redis = $this->createRedisClient([
            'server' => $servers[0],
            'cluster' => [
                'enabled' => true,
            ]
        ]);

        $ClusterMapProperty = new \ReflectionProperty(AbstractRedisClient::class, 'ClusterMap');
        $ClusterMapProperty->setAccessible(true);
        /** @var ClusterMap $ClusterMap */
        $ClusterMap = $ClusterMapProperty->getValue($Redis);

        $this->assertSame([], $ClusterMap->getClusters());
        $Redis->_syncClusterSlotsFromRedisServer();
        $this->assertSame(
            [
                5460  => '127.0.0.1:7001',
                10922 => '127.0.0.1:7002',
                16383 => '127.0.0.1:7003',
            ],
            $ClusterMap->getClusters()
        );
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeProtocolCommand
     */
    public function test_syncClusterSlotsFromRedisServerOnErrorMoved() {
        $servers = $this->getServers();
        $Redis = $this->createRedisClient([
            'server' => $servers[0],
            'cluster' => [
                'enabled' => true,
                'init_on_error_moved' => true,
            ]
        ]);

        $ClusterMapProperty = new \ReflectionProperty(AbstractRedisClient::class, 'ClusterMap');
        $ClusterMapProperty->setAccessible(true);
        /** @var ClusterMap $ClusterMap */
        $ClusterMap = $ClusterMapProperty->getValue($Redis);

        $this->assertSame([], $ClusterMap->getClusters());
        $Redis->set('foo', 'bar');
        $this->assertSame(
            [
                5460  => '127.0.0.1:7001',
                10922 => '127.0.0.1:7002',
                16383 => '127.0.0.1:7003',
            ],
            $ClusterMap->getClusters()
        );
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeProtocolCommand
     */
    public function test_syncClusterSlotsFromRedisServerOnInit() {
        $servers = $this->getServers();
        $Redis = $this->createRedisClient([
            'server' => $servers[0],
            'cluster' => [
                'enabled' => true,
                'init_on_start' => true,
            ]
        ]);

        $ClusterMapProperty = new \ReflectionProperty(AbstractRedisClient::class, 'ClusterMap');
        $ClusterMapProperty->setAccessible(true);
        /** @var ClusterMap $ClusterMap */
        $ClusterMap = $ClusterMapProperty->getValue($Redis);
        $this->assertSame(
            [
                5460  => '127.0.0.1:7001',
                10922 => '127.0.0.1:7002',
                16383 => '127.0.0.1:7003',
            ],
            $ClusterMap->getClusters()
        );
    }

    /**
     * @see \RedisClient\Client\AbstractRedisClient::executeProtocolCommand
     */
    public function test_addClusterSlotsOnError() {
        $servers = $this->getServers();
        $Redis = $this->createRedisClient([
            'server' => $servers[0],
            'cluster' => [
                'enabled' => true,
            ]
        ]);

        $ClusterMapProperty = new \ReflectionProperty(AbstractRedisClient::class, 'ClusterMap');
        $ClusterMapProperty->setAccessible(true);
        /** @var ClusterMap $ClusterMap */
        $ClusterMap = $ClusterMapProperty->getValue($Redis);

        $this->assertSame([], $ClusterMap->getClusters());
        $Redis->set('foo', 'bar');
        $this->assertSame([12182 => '127.0.0.1:7003'], $ClusterMap->getClusters());
        $Redis->set('foo1', 'bar');
        $this->assertSame([12182 => '127.0.0.1:7003'], $ClusterMap->getClusters());
        $Redis->set('foo2', 'bar');
        $this->assertSame(
            [
                1044  => '127.0.0.1:7001',
                12182 => '127.0.0.1:7003'
            ],
            $ClusterMap->getClusters()
        );
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\ClusterCommandsTrait::clusterNodes
     */
    public function test_clusterNodes() {
        $Redis = $this->getRedisClient();
        $nodes = $Redis->clusterNodes();
        foreach ($this->getServers() as $server) {
            $this->assertSame(true, 0 < strpos($nodes, $server));
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\ClusterCommandsTrait::clusterNodes
     */
    public function test_validPipeline() {
        $Redis = $this->getRedisClient();
        $result = $Redis->pipeline(function($Pipeline) {
            /** @var Pipeline $Pipeline */
            $Pipeline->set('user{42}', 'Alexander');
            $Pipeline->set('country{42}', 'UK');
            $Pipeline->set('city{42}', 'London');
            $Pipeline->mget(['user{42}', 'country{42}', 'city{42}']);
        });

        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(true, $result[0]);
        $this->assertSame(true, $result[1]);
        $this->assertSame(true, $result[2]);
        $this->assertSame('Alexander', $result[3][0]);
        $this->assertSame('UK', $result[3][1]);
        $this->assertSame('London', $result[3][2]);
    }

    /**
     * @see \RedisClient\Command\Traits\Version3x0\ClusterCommandsTrait::clusterNodes
     */
    public function test_invalidPipeline() {
        $Redis = $this->getRedisClient();
        $result = $Redis->pipeline(function($Pipeline) {
            /** @var Pipeline $Pipeline */
            $Pipeline->set('user{42}', 'Alexander');
            $Pipeline->set('user{43}', 'Irina');
            $Pipeline->set('user{44}', 'Aram');
            $Pipeline->mget(['user{42}', 'user{43}', 'user{44}']);
        });

        $this->assertSame(true, is_array($result));
        $this->assertSame(4, count($result));
        $this->assertSame(true, $result[0]);
        $this->assertSame(true, $result[1] instanceof MovedResponseException);
        $this->assertSame(true, $result[2] instanceof MovedResponseException);
        $this->assertSame(true, $result[3] instanceof CrossSlotResponseException);

        $this->assertSame('127.0.0.1:7001', $result[1]->getServer());
        $this->assertSame(3937, $result[1]->getSlot());
        $this->assertSame('127.0.0.1:7003', $result[2]->getServer());
        $this->assertSame(16262, $result[2]->getSlot());
        $this->assertSame('CROSSSLOT Keys in request don\'t hash to the same slot', $result[3]->getMessage());
    }

}
