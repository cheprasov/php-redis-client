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

/**
 * @see \RedisClient\Command\Traits\Version3x2\ScriptingCommandsTrait
 */
class ScriptingCommandsTest extends \Test\Integration\Version3x0\ScriptingCommandsTest {

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
