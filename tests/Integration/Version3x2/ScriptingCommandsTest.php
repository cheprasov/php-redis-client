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

include_once(__DIR__. '/../Version3x0/ScriptingCommandsTest.php');

use RedisClient\Client\Version\RedisClient3x2;
use Test\Integration\Version3x0\ScriptingCommandsTest as ScriptingCommandsTestVersion3x0;

/**
 * @see \RedisClient\Command\Traits\Version3x2\ScriptingCommandsTrait
 */
class ScriptingCommandsTest extends ScriptingCommandsTestVersion3x0 {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_3x2_1;

    /**
     * @var RedisClient3x2;
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
     * @see \RedisClient\Command\Traits\Version3x2\ScriptingCommandsTrait::scriptDebug
     */
    public function test_scriptDebug() {
        $Redis = static::$Redis;
        $this->assertSame(true, $Redis->scriptDebug('SYNC'));
        $this->assertSame(true, $Redis->scriptDebug('YES'));
        $this->assertSame(true, $Redis->scriptDebug('NO'));
    }
}
