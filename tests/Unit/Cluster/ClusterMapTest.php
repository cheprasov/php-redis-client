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
namespace Test\Unit\Cluster;

use RedisClient\Cluster\ClusterMap;
use RedisClient\Command\Response\ResponseParser;
use RedisClient\RedisClient;

/**
 * @see RedisClient\Cluster\ClusterMap
 */
class ClusterMapTest extends \PHPUnit_Framework_TestCase {

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
     * @see RedisClient\Cluster\ClusterMap::getSlotByKey
     * @dataProvider provider_getSlotByKey
     * @param string $key
     * @param int $slot
     */
    public function test_getSlotByKey($key, $slot) {
        $this->assertSame($slot, ClusterMap::getSlotByKey($key));
    }

    public function test_get() {
        $this->markTestSkipped();
        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'timeout' => 5,
            'cluster' => [
                'enabled' => true,
            ]
        ]);

        for ($i = 0; $i < 100; ++$i) {
            $key = 'foo' . $i;
            $this->assertSame(true, $Redis->set($key, $i));
        }

        for ($i = 0; $i < 100; ++$i) {
            $key = 'foo' . $i;
            $this->assertSame($i, (int)$Redis->get($key));
        }
    }

}
