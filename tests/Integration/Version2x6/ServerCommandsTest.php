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
 * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait
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
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        static::$Redis->flushall();
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::bgrewriteaof
     */
    public function _test_bgrewriteaof() {
        $Redis = static::$Redis;

        $this->assertSame('Background append only file rewriting started', $Redis->bgrewriteaof());
        try {
            $this->assertSame(true, (bool) $Redis->bgrewriteaof());
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::bgsave
     */
    public function _test_bgsave() {
        $Redis = static::$Redis;

        $this->assertSame('Background saving started', $Redis->bgsave());
        try {
            $this->assertSame(true, (bool) $Redis->bgsave());
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::clientGetname
     */
    public function test_clientGetname() {
        $Redis = static::$Redis;

        $this->assertSame(null, $Redis->clientGetname());
        $this->assertSame(true, $Redis->clientSetname('test-connection'));
        $this->assertSame('test-connection', $Redis->clientGetname());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::clientList
     */
    public function test_clientList() {
        $Redis = static::$Redis;

        $result = $Redis->clientList();
        $this->assertSame(true, is_array($result));
        $this->assertSame(true, isset($result[0]['addr']));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::configGet
     */
    public function test_configGet() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->configGet('not-exists-param'));
        $this->assertSame(['dbfilename' => 'dump.rdb'], $Redis->configGet('dbfilename'));
        $this->assertSame(['dbfilename' => 'dump.rdb'], $Redis->configGet('db*name'));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::configResetstat
     */
    public function test_configResetstat() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->configResetstat());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::dbsize
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
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::debugObject
     */
    public function test_debugObject() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->set('foo', 'bar'));
        $this->assertSame(true, is_string($Redis->debugObject('foo')));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::flushall
     */
    public function test_flushall() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushall());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::flushdb
     */
    public function test_flushdb() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->flushdb());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::info
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

        $info = $Redis->info('all');
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
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::info
     */
    public function test_lastsave() {
        $Redis = static::$Redis;

        $this->assertSame(true, is_int($Redis->lastsave()));
        $this->assertGreaterThan(0, $Redis->lastsave());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::monitor
     */
    public function _test_monitor() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->monitor());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::save
     */
    public function test_save() {
        $Redis = static::$Redis;

        $this->assertSame(true, $Redis->save());
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::slowlog
     */
    public function _test_showlog() {
        $Redis = static::$Redis;

        $this->assertSame([], $Redis->slowlog('GET', 2));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ServerCommandsTrait::time
     */
    public function test_time() {
        $Redis = static::$Redis;

        $this->assertSame(1, preg_match('/^\d+\.\d+$/', $Redis->time()));
    }

}
