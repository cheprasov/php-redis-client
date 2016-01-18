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
namespace Test\Integration\Version2x6;

use RedisClient\Client\Version\RedisClient2x6;
use RedisClient\Exception\ErrorResponseException;

/**
 * @see ServerCommandsTrait
 */
class ServerCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis2;

    /**
     * @var array
     */
    protected static $fields;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass() {
        static::$Redis = new RedisClient2x6([
            'server' =>  static::TEST_REDIS_SERVER_1,
            'timeout' => 2,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass() {
        static::$Redis->flushall();
        static::$Redis->scriptFlush();
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
        static::$Redis->scriptFlush();
    }

    /**
     * @see ServerCommandsTrait::bgrewriteaof
     */
    public function _test_bgrewriteaof() {
        $Redis = static::$Redis;

        $this->assertSame('Background append only file rewriting started', $Redis->bgrewriteaof());
        try {
            $this->assertSame(true, (bool) $Redis->bgrewriteaof());
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see ServerCommandsTrait::bgsave
     */
    public function _test_bgsave() {
        $Redis = static::$Redis;

        $this->assertSame('Background saving started', $Redis->bgsave());
        try {
            $this->assertSame(true, (bool) $Redis->bgsave());
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see ServerCommandsTrait::clientGetname
     */
    public function test_clientGetname() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->clientGetname());
        $this->assertSame(true, $Redis->clientSetname('test-connection'));
        $this->assertSame('test-connection', $Redis->clientGetname());
    }

    /**
     * @see ServerCommandsTrait::clientList
     */
    public function test_clientList() {
        $Redis = static::$Redis;

        $result = $Redis->clientList();
        $this->assertSame(true, is_array($result));
        $this->assertSame(true, isset($result[0]['addr']));
    }

    /**
     * @see ServerCommandsTrait::configGet
     */
    public function test_configGet() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->configGet('not-exists-param'));
        $this->assertSame(['dbfilename' => 'dump.rdb'], $Redis->configGet('dbfilename'));
        $this->assertSame(['dbfilename' => 'dump.rdb'], $Redis->configGet('db*name'));
    }

    /**
     * @see ServerCommandsTrait::configResetstat
     */
    public function test_configResetstat() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->configResetstat());
    }

    /**
     * @see ServerCommandsTrait::dbsize
     */
    public function test_dbsize() {
        $Redis = static::$Redis;

        $this->assertSame(0, $Redis->dbsize());
        $this->assertSame(true, $Redis->set('foo', 'bar'));
        $this->assertSame(1, $Redis->dbsize());
        $this->assertSame(true, $Redis->set('bar', 'foo'));
        $this->assertSame(2, $Redis->dbsize());
    }

    /**
     * @see ServerCommandsTrait::debugObject
     */
    public function test_debugObject() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('foo', 'bar'));
        $this->assertSame(true, is_string($Redis->debugObject('foo')));
    }

    /**
     * @see ServerCommandsTrait::flushall
     */
    public function test_flushall() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushall());
    }

    /**
     * @see ServerCommandsTrait::flushdb
     */
    public function test_flushdb() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushdb());
    }

    /**
     * @see ServerCommandsTrait::info
     */
    public function test_info() {
        $Redis = static::$Redis;

        $info = $Redis->info();
        $this->assertSame(true, is_array($info));
        $this->assertSame(true, is_array($info['Server']));
        $this->assertSame(true, is_array($info['Clients']));
        $this->assertSame(true, is_array($info['Memory']));
        $this->assertSame(true, is_array($info['Persistence']));
        $this->assertSame(true, is_array($info['Stats']));
        $this->assertSame(true, is_array($info['Replication']));
        $this->assertSame(true, is_array($info['CPU']));
        $this->assertSame(true, is_array($info['Keyspace']));

        $this->assertSame($info['Server'], $Redis->info('Server'));
        $this->assertSame($info['Clients'], $Redis->info('Clients'));
        $this->assertSame($info['Persistence'], $Redis->info('Persistence'));
        $this->assertSame($info['Replication'], $Redis->info('Replication'));

        $this->assertSame(true, isset($info['Server']['redis_version']));
    }

    /**
     * @see ServerCommandsTrait::info
     */
    public function test_lastsave() {
        $Redis = static::$Redis;

        $this->assertSame(true, is_int($Redis->lastsave()));
        $this->assertGreaterThan(0, $Redis->lastsave());
    }

    /**
     * @see ServerCommandsTrait::monitor
     */
    public function _test_monitor() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->monitor());
    }

    /**
     * @see ServerCommandsTrait::save
     */
    public function test_save() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->save());
    }

    /**
     * @see ServerCommandsTrait::slowlog
     */
    public function _test_showlog() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->slowlog('GET', 2));
    }

    /**
     * @see ServerCommandsTrait::time
     */
    public function test_time() {
        $Redis = static::$Redis;

        $this->assertSame(1, preg_match('/^\d+\.\d+$/', $Redis->time()));
    }

}
