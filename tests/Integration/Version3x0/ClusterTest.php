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
namespace Test\Integration\Version3x0;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Cluster\ClusterMap;

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
        $this->assertSame([13431 => '127.0.0.1:7003'], $ClusterMap->getClusters());
        $Redis->set('foo2', 'bar');
        $this->assertSame(
            [
                1044  => '127.0.0.1:7001',
                13431 => '127.0.0.1:7003'
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

}
