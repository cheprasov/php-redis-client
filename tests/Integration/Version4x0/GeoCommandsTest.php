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
namespace Test\Integration\Version4x0;

use RedisClient\Exception\ErrorResponseException;

include_once(__DIR__ . '/../Version3x2/GeoCommandsTest.php');

class GeoCommandsTest extends \Test\Integration\Version3x2\GeoCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version3x2\GeoCommandsTrait::geohash
     */
    public function test_geohash() {
        $Redis = static::$Redis;

        $this->assertSame(2, $Redis->geoadd('Sicily', [
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame(['sqc8b49rny0'], $Redis->geohash('Sicily', ['Palermo']));
        $this->assertSame(['sqc8b49rny0', 'sqdtr74hyu0'], $Redis->geohash('Sicily', ['Palermo', 'Catania']));
        $this->assertSame(['sqdtr74hyu0'], $Redis->geohash('Sicily', ['Catania']));
        $this->assertSame([null], $Redis->geohash('Sicily', ['foo']));
        $this->assertSame([null, 'sqc8b49rny0'], $Redis->geohash('Sicily', ['foo', 'Palermo']));
        $this->assertSame([null, 'sqc8b49rny0', null], $Redis->geohash('Sicily', ['foo', 'Palermo', 'bar']));
        $this->assertSame(['sqc8b49rny0', 'sqc8b49rny0'], $Redis->geohash('Sicily', ['Palermo', 'Palermo']));

        $this->assertSame([null], $Redis->geohash('foo', ['a']));
        $this->assertSame([null, null, null], $Redis->geohash('foo', ['a', 'b', 'c']));

        $this->assertSame(null, $Redis->geodist('Sicily', 'Catania', 'foo', 'ft'));
        $this->assertSame(null, $Redis->geodist('Sicily', 'bar', 'foo'));

        $Redis->set('foo', 'bar');
        $this->setExpectedException(ErrorResponseException::class);
        $Redis->geodist('foo', 'bar', 'foo');
    }

}
