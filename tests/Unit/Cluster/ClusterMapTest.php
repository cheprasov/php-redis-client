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
namespace Test\Unit\Cluster;

use PHPUnit\Framework\TestCase;
use RedisClient\Cluster\ClusterMap;
use RedisClient\RedisClient;

/**
 * @see \RedisClient\Cluster\ClusterMap
 */
class ClusterMapTest extends TestCase {

    protected function getClusterMap($RedisClient = null, $config = []) {
        if (!$RedisClient) {
            /** @var RedisClient|\PHPUnit\Framework\MockObject\MockObject $RedisClient */
            $RedisClient = $this->getMockBuilder(RedisClient::class)
                ->disableOriginalConstructor()
                ->getMock();
        }
        return new ClusterMap($RedisClient, $config);
    }

    public function provider_getSlotByKey() {
        return [
            ['FOO{BAR}', 14215],
            ['foo{BAR}', 14215],
            ['foo{bar}', 5061],
            ['{bar}', 5061],
            ['{bar}}', 5061],
            ['bar', 5061],
            ['{bar}foo', 5061],
            ['{bar}}foo', 5061],
            ['bar{bar}', 5061],
            ['{bar}{bar}', 5061],
            ['bar{}', 16328],
            ['{}', 15257],
            ['{{}}', 4092],
            ['{{bar}}', 4015],
            ['{foo{bar}foo}', 15278],
            ['foo', 12182],
            ['', 0],
            ['}foo{', 8453],
            ['{bar', 4015],
            ['}{bar}', 5061],
            ['{}{bar}', 11272],
            ['}{}{bar}', 2656],
            ['}}{bar}', 5061],
            ['{foo}{bar}', 12182],
            ['bar{', 14630],
            ['bar}', 6624],
            ['bar}{', 13736],
            ['0', 13907],
            ['{0}', 13907],
            ['1{0}2', 13907],
            ['12{0}', 13907],
            ['{0}12', 13907],
            ['{-}', 13775],
            ['{}}', 14790],
            ['{{}', 4092],
            ['{{{{{}', 12236],
            ['}}}', 2918],
            ['{{{', 13222],
            ['{', 4092],
            ['}', 12090],
            ['profile{42}', 8000],
            ['user{42}', 8000],
            ['message{42}', 8000],
            ['{42}user', 8000],
            ['user{42}.friends', 8000],
            [42, 8000],
            ['1{42}3', 8000],
            ['{-42}', 7127],
            ['-42', 7127],
            ['Alexander Cheprasov', 377],
            ['{Alexander Cheprasov} was here', 377],
            ['3194', 3194],
            [3194, 3194],
            ['1', 9842],
            ['11', 4310],
            ['111', 8771],
            ['1111', 14366],
            ['11111', 7841],
            [' ', 9314],
            ['{ }', 9314],
        ];
    }

    /**
     * @see \RedisClient\Cluster\ClusterMap::getSlotByKey
     * @dataProvider provider_getSlotByKey
     * @param string $key
     * @param int $slot
     */
    public function test_getSlotByKey($key, $slot) {
        $this->assertSame($slot, ClusterMap::getSlotByKey($key));
    }

    public function provider_getServerBySlot() {
        return [
            [  0, 'server-1'],
            [ 10, 'server-1'],
            [ 50, 'server-1'],
            [ 99, 'server-1'],
            [100, 'server-1'],

            [101, 'server-2'],
            [110, 'server-2'],
            [150, 'server-2'],
            [199, 'server-2'],
            [200, 'server-2'],

            [201, 'server-3'],
            [275, 'server-3'],
            [299, 'server-3'],
            [300, 'server-3'],

            [301, 'server-4'],
            [399, 'server-4'],
            [400, 'server-4'],

            [401, 'server-5'],
            [488, 'server-5'],
            [490, 'server-5'],
            [500, 'server-5'],

            [501, 'server-5'],
            [600, 'server-5'],
        ];
    }

    /**
     * @see \RedisClient\Cluster\ClusterMap::getServerBySlot
     * @dataProvider provider_getServerBySlot
     * @param int $slot
     * @param string $expect
     */
    public function test_getServerBySlot($slot, $expect) {
        $ClusterMap = $this->getClusterMap();
        $ClusterMap->setClusters([
            100 => 'server-1',
            200 => 'server-2',
            300 => 'server-3',
            400 => 'server-4',
            500 => 'server-5',
        ]);
        $this->assertSame($expect, $ClusterMap->getServerBySlot($slot));
    }

    /**
     * @see \RedisClient\Cluster\ClusterMap::setClusters
     */
    public function test_setClusters() {
        $ClusterMap = $this->getClusterMap();
        $Property = new \ReflectionProperty(ClusterMap::class, 'clusters');
        $Property->setAccessible(true);
        $this->assertSame([], $Property->getValue($ClusterMap));

        $ClusterMap->setClusters([
            100 => 'server-1',
            300 => 'server-3',
            200 => 'server-2',
            500 => 'server-5',
            400 => 'server-4',
        ]);

        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                300 => 'server-3',
                400 => 'server-4',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->setClusters([
            300 => 'server-3',
            100 => 'server-1',
            200 => 'server-2',
        ]);
        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                300 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );
    }

    /**
     * @see \RedisClient\Cluster\ClusterMap::addCluster
     */
    public function test_addCluster() {
        $ClusterMap = $this->getClusterMap();
        $Property = new \ReflectionProperty(ClusterMap::class, 'clusters');
        $Property->setAccessible(true);
        $this->assertSame([], $Property->getValue($ClusterMap));

        $ClusterMap->addCluster(300, 'server-3');
        $this->assertSame(
            [
                300 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(100, 'server-1');
        $this->assertSame(
            [
                100 => 'server-1',
                300 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(200, 'server-2');
        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                300 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(500, 'server-5');
        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                300 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(400, 'server-5');
        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                300 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(350, 'server-3');
        $this->assertSame(
            [
                100 => 'server-1',
                200 => 'server-2',
                350 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(150, 'server-1');
        $this->assertSame(
            [
                150 => 'server-1',
                200 => 'server-2',
                350 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(170, 'server-2');
        $this->assertSame(
            [
                150 => 'server-1',
                200 => 'server-2',
                350 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(10, 'server-0');
        $this->assertSame(
            [
                10  => 'server-0',
                150 => 'server-1',
                200 => 'server-2',
                350 => 'server-3',
                500 => 'server-5',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(500, 'server-3');
        $this->assertSame(
            [
                10  => 'server-0',
                150 => 'server-1',
                200 => 'server-2',
                500 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );

        $ClusterMap->addCluster(600, 'server-3');
        $this->assertSame(
            [
                10  => 'server-0',
                150 => 'server-1',
                200 => 'server-2',
                600 => 'server-3',
            ],
            $Property->getValue($ClusterMap)
        );
    }

}
