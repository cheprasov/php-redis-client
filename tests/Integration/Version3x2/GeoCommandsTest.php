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
namespace Test\Integration\Version3x2;

use RedisClient\Client\Version\RedisClient3x2;
use RedisClient\Exception\ErrorResponseException;

/**
 * @see HyperLogLogCommandsTrait
 */
class GeoCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;

    /**
     * @var RedisClient3x2
     */
    protected static $Redis;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient3x2([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function setUp() {
        static::$Redis->flushall();
    }

    /**
     * @see GeoCommandsTrait::geoadd
     */
    public function test_geoadd() {
        $Redis = static::$Redis;

        $this->assertSame(2, $Redis->geoadd('Sicily', [
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame('166274.15156960033', $Redis->geodist('Sicily', 'Palermo', 'Catania'));
        $this->assertSame(['Catania'], $Redis->georadius('Sicily', 15, 37, 100, 'km'));
        $this->assertSame(['Palermo', 'Catania'], $Redis->georadius('Sicily', 15, 37, 200, 'km'));

        $this->assertSame(0, $Redis->geoadd('Sicily', ['Palermo' => ['13.361389', '38.115556']]));

        $this->assertSame(1, $Redis->geoadd('bar', ['Palermo' => ['13.361389', '38.115556']]));
        $this->assertSame(0, $Redis->geoadd('bar', ['Palermo' => ['13.361389', '38.115556']]));
        $this->assertSame(1, $Redis->geoadd('bar', ['Catania' => ['15.087269', '37.502669']]));

        $Redis->set('foo', 'bar');
        try {
            $Redis->geoadd('foo', ['Palermo' => ['13.361389', '38.115556']]);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see GeoCommandsTrait::geodist
     */
    public function test_geodist() {
        $Redis = static::$Redis;

        $this->assertSame(2, $Redis->geoadd('Sicily', [
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame('166274.15156960033', $Redis->geodist('Sicily', 'Palermo', 'Catania'));
        $this->assertSame('166274.15156960033', $Redis->geodist('Sicily', 'Palermo', 'Catania', 'm'));
        $this->assertSame('166.27415156960032', $Redis->geodist('Sicily', 'Palermo', 'Catania', 'km'));
        $this->assertSame('103.31822459492733', $Redis->geodist('Sicily', 'Palermo', 'Catania', 'mi'));
        $this->assertSame('545518.86997900368', $Redis->geodist('Sicily', 'Palermo', 'Catania', 'ft'));

        $this->assertSame('166274.15156960033', $Redis->geodist('Sicily', 'Catania', 'Palermo'));
        $this->assertSame('166274.15156960033', $Redis->geodist('Sicily', 'Catania', 'Palermo', 'm'));
        $this->assertSame('166.27415156960032', $Redis->geodist('Sicily', 'Catania', 'Palermo', 'km'));
        $this->assertSame('103.31822459492733', $Redis->geodist('Sicily', 'Catania', 'Palermo', 'mi'));
        $this->assertSame('545518.86997900368', $Redis->geodist('Sicily', 'Catania', 'Palermo', 'ft'));

        $this->assertSame('0', $Redis->geodist('Sicily', 'Catania', 'Catania'));
        $this->assertSame('0', $Redis->geodist('Sicily', 'Catania', 'Catania', 'm'));
        $this->assertSame('0', $Redis->geodist('Sicily', 'Catania', 'Catania', 'km'));
        $this->assertSame('0', $Redis->geodist('Sicily', 'Catania', 'Catania', 'mi'));
        $this->assertSame('0', $Redis->geodist('Sicily', 'Catania', 'Catania', 'ft'));

        $this->assertSame(null, $Redis->geodist('Sicily', 'Catania', 'foo', 'ft'));
        $this->assertSame(null, $Redis->geodist('Sicily', 'bar', 'foo'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->geodist('foo', 'Catania', 'Palermo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see GeoCommandsTrait::geohash
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

        $this->assertSame([], $Redis->geohash('foo', ['a']));
        $this->assertSame([], $Redis->geohash('foo', ['a', 'b', 'c']));

        $this->assertSame(null, $Redis->geodist('Sicily', 'Catania', 'foo', 'ft'));
        $this->assertSame(null, $Redis->geodist('Sicily', 'bar', 'foo'));

        $Redis->set('foo', 'bar');
        try {
            $Redis->geodist('foo', 'bar', 'foo');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see GeoCommandsTrait::geopos
     */
    public function test_geopos() {
        $Redis = static::$Redis;

        $this->assertSame(2, $Redis->geoadd('Sicily', [
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame([
            ['13.361389338970184', '38.115556395496299'],
            ['15.087267458438873', '37.50266842333162'],
            null
        ], $Redis->geopos('Sicily', ['Palermo', 'Catania', 'NonExisting']));

        $this->assertSame([
            ['13.361389338970184', '38.115556395496299'],
            ['13.361389338970184', '38.115556395496299'],
        ], $Redis->geopos('Sicily', ['Palermo', 'Palermo']));

        $Redis->set('foo', 'bar');
        try {
            $Redis->geopos('foo', ['bar', 'foo']);
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }


    /**
     * @see GeoCommandsTrait::georadius
     */
    public function test_georadius() {
        $Redis = static::$Redis;

        $this->assertSame(2, $Redis->geoadd('Sicily', [
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame(['Catania'], $Redis->georadius('Sicily', 15, 37, 100, 'km'));
        $this->assertSame(['Palermo', 'Catania'], $Redis->georadius('Sicily', 15, 37, 200, 'km'));
        $this->assertSame(['Catania', 'Palermo'], $Redis->georadius('Sicily', 15, 37, 200, 'km', false, false, false, null, true));
        $this->assertSame(['Palermo', 'Catania'], $Redis->georadius('Sicily', 15, 37, 200, 'km', false, false, false, null, false));
        $this->assertSame(['Palermo'], $Redis->georadius('Sicily', 15, 37, 200, 'km', false, false, false, 1, false));
        $this->assertSame(['Catania'], $Redis->georadius('Sicily', 15, 37, 200, 'km', false, false, false, 1, true));

        $this->assertSame([
                'Palermo' => ['190.4424'],
                'Catania' => ['56.4413']
            ], $Redis->georadius('Sicily', 15, 37, 200, 'km', false, true)
        );
        $this->assertSame([
            'Catania' => ['56.4413', 3479447370796909, ['15.087267458438873', '37.50266842333162']]
            ], $Redis->georadius('Sicily', 15, 37, 100, 'km', true, true, true)
        );
        $this->assertSame([
            'Catania' => ['56.4413', 3479447370796909, ['15.087267458438873', '37.50266842333162']]
            ], $Redis->georadius('Sicily', 15, 37, 100, 'km', true, true, true)
        );
        $this->assertSame([
            'Palermo' => ['190.4424', 3479099956230698, ['13.361389338970184', '38.115556395496299']],
            'Catania' => ['56.4413', 3479447370796909, ['15.087267458438873', '37.50266842333162']]
            ], $Redis->georadius('Sicily', 15, 37, 200, 'km', true, true, true)
        );
        $this->assertSame([
            'Palermo' => ['190.4424', 3479099956230698, ['13.361389338970184', '38.115556395496299']],
            'Catania' => ['56.4413', 3479447370796909, ['15.087267458438873', '37.50266842333162']]
            ], $Redis->georadius('Sicily', 15, 37, 200, 'km', true, true, true, null, false)
        );
        $this->assertSame([
            'Catania' => [['15.087267458438873', '37.50266842333162']]
            ], $Redis->georadius('Sicily', 15, 37, 100, 'km', true)
        );

        $Redis->set('foo', 'bar');
        try {
            $Redis->georadius('foo', 15, 37, 100, 'km');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see GeoCommandsTrait::georadiusbymember
     */
    public function test_georadiusbymember() {
        $Redis = static::$Redis;

        $this->assertSame(3, $Redis->geoadd('Sicily', [
            'Agrigento' => ['13.583333', '37.316667'],
            'Palermo' => ['13.361389', '38.115556'],
            'Catania' => ['15.087269', '37.502669']
        ]));

        $this->assertSame(['Agrigento', 'Palermo'], $Redis->georadiusbymember('Sicily', 'Agrigento', 100, 'km'));
        $this->assertSame(['Agrigento'], $Redis->georadiusbymember('Sicily', 'Agrigento', 0, 'km'));
        $this->assertSame(['Agrigento', 'Palermo'], $Redis->georadiusbymember('Sicily', 'Agrigento', 100, 'km', null, null, null, null, true));
        $this->assertSame(['Palermo', 'Agrigento'], $Redis->georadiusbymember('Sicily', 'Agrigento', 100, 'km', null, null, null, null, false));

        $this->assertSame([
            'Palermo' => ['90.9778', 3479099956230698, ['13.361389338970184', '38.115556395496299']],
            'Agrigento' => ['0.0000', 3479030013248308, ['13.583331406116486', '37.316668049938166']],
            ], $Redis->georadiusbymember('Sicily', 'Agrigento', 100, 'km', true, true, true, null, false)
        );

        $Redis->set('foo', 'bar');
        try {
            $Redis->georadiusbymember('foo', 'Agrigento', 100, 'km');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

}
