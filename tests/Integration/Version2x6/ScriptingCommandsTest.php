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
 * @see ScriptingCommandsTrait
 */
class ScriptingCommandsTest extends \PHPUnit_Framework_TestCase {

    const TEST_REDIS_SERVER_1 = TEST_REDIS_SERVER_2x6_1;

    /**
     * @var RedisClient2x6
     */
    protected static $Redis;

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

    public function test_eval() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";
        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalScript($script, ['a', 'b'], ['c', 'd']));
        $this->assertSame(['a', 'b', 'd', 'e'], $Redis->evalScript($script, ['a', 'b', 'c'], ['d', 'e']));
        $this->assertSame(['a', 'b'], $Redis->evalScript($script, ['a', 'b', 'c']));
        $this->assertSame([], $Redis->evalScript($script));

        try {
            $Redis->evalScript('Script with error');
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

    public function test_evalsha() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        try {
            $Redis->evalsha(sha1($script));
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }

        $this->assertSame(sha1($script), $Redis->scriptLoad($script));

        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalsha(sha1($script), ['a', 'b'], ['c', 'd']));
        $this->assertSame(['a', 'b', 'd', 'e'], $Redis->evalsha(sha1($script), ['a', 'b', 'c'], ['d', 'e']));
        $this->assertSame(['a', 'b'], $Redis->evalsha(sha1($script), ['a', 'b', 'c']));
        $this->assertSame([], $Redis->evalsha(sha1($script)));
    }

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

    public function test_scriptkill() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";
        $this->assertSame(['a', 'b', 'c', 'd'], $Redis->evalScript($script, ['a', 'b'], ['c', 'd']));

        try {
            $Redis->scriptKill();
            $this->assertTrue(false);
        } catch (\Exception $Ex) {
            $this->assertInstanceOf(ErrorResponseException::class, $Ex);
        }
    }

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
