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
namespace Test\Integration\Version2x6;

include_once(__DIR__ . '/../BaseVersionTest.php');

use RedisClient\Exception\ErrorResponseException;

/**
 * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait
 */
class ScriptingCommandsTest extends \Test\Integration\BaseVersionTest {

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::evalScript
     */
    public function test_evalScript() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";
        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalScript($script, ['a', 'b'], ['c', 'd']));
        $this->assertSame(['a', 'b', 'd', 'e'], $Redis->evalScript($script, ['a', 'b', 'c'], ['d', 'e']));
        $this->assertSame(['a', 'b'], $Redis->evalScript($script, ['a', 'b', 'c']));
        $this->assertSame([], $Redis->evalScript($script));

        try {
            $Redis->evalScript('Script with error');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::eval
     */
    public function test_eval() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";
        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->eval($script, ['a', 'b'], ['c', 'd']));
        $this->assertSame(['a', 'b', 'd', 'e'], $Redis->eval($script, ['a', 'b', 'c'], ['d', 'e']));
        $this->assertSame(['a', 'b'], $Redis->eval($script, ['a', 'b', 'c']));
        $this->assertSame([], $Redis->eval($script));

        try {
            $Redis->eval('Script with error');
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::evalsha
     */
    public function test_evalsha() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        try {
            $Redis->evalsha(sha1($script));
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(sha1($script), $Redis->scriptLoad($script));

        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalsha(sha1($script), ['a', 'b'], ['c', 'd']));
        $this->assertSame(['a', 'b', 'd', 'e'], $Redis->evalsha(sha1($script), ['a', 'b', 'c'], ['d', 'e']));
        $this->assertSame(['a', 'b'], $Redis->evalsha(sha1($script), ['a', 'b', 'c']));
        $this->assertSame([], $Redis->evalsha(sha1($script)));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::scriptexists
     */
    public function test_scriptexists() {
        $Redis = static::$Redis;

        $script1 = "return {KEYS[1]}";
        $script2 = "return {KEYS[1],KEYS[2],ARGV[1]}";
        $script3 = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        $this->assertSame([0], $Redis->scriptExists($script1));
        $this->assertSame(sha1($script1), $Redis->scriptLoad($script1));
        $this->assertSame([1], $Redis->scriptExists(sha1($script1)));

        $this->assertSame([0], $Redis->scriptExists(sha1($script2)));
        $this->assertSame(sha1($script2), $Redis->scriptLoad($script2));
        $this->assertSame([1], $Redis->scriptExists(sha1($script2)));

        $this->assertSame([1, 1, 0], $Redis->scriptExists([sha1($script1), sha1($script2), sha1($script3)]));

        $this->assertSame([0], $Redis->scriptExists($script3));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::scriptflush
     */
    public function test_scriptflush() {
        $Redis = static::$Redis;

        $script1 = "return {KEYS[1]}";
        $script2 = "return {KEYS[1],KEYS[2],ARGV[1]}";
        $script3 = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        $this->assertSame(sha1($script1), $Redis->scriptLoad($script1));
        $this->assertSame(sha1($script2), $Redis->scriptLoad($script2));
        $this->assertSame(sha1($script3), $Redis->scriptLoad($script3));

        $this->assertSame([1, 1, 1], $Redis->scriptExists([sha1($script1), sha1($script2), sha1($script3)]));
        $this->assertSame(true, $Redis->scriptFlush());
        $this->assertSame([0, 0, 0], $Redis->scriptExists([sha1($script1), sha1($script2), sha1($script3)]));
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::scriptkill
     */
    public function test_scriptkill() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";
        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalScript($script, ['a', 'b'], ['c', 'd']));

        try {
            $Redis->scriptKill();
            $this->assertFalse('Expect Exception');
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    /**
     * @see \RedisClient\Command\Traits\Version2x6\ScriptingCommandsTrait::scriptload
     */
    public function test_scriptload() {
        $Redis = static::$Redis;

        $script1 = "return {KEYS[1]}";
        $script2 = "return {KEYS[1],KEYS[2],ARGV[1]}";
        $script3 = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        $this->assertSame([0, 0, 0], $Redis->scriptExists([sha1($script1), sha1($script2), sha1($script3)]));

        $this->assertSame(sha1($script1), $Redis->scriptLoad($script1));
        $this->assertSame(sha1($script2), $Redis->scriptLoad($script2));
        $this->assertSame(sha1($script3), $Redis->scriptLoad($script3));

        $this->assertSame([1, 1, 1], $Redis->scriptExists([sha1($script1), sha1($script2), sha1($script3)]));
    }

}
