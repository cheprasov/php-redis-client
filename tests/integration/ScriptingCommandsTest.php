<?php

namespace Test\Integration;

include_once(__DIR__. '/AbstractCommandsTest.php');

use RedisClient\Exception\ErrorResponseException;

class ScriptingCommandsTest extends AbstractCommandsTest {

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
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('ERR Error compiling script (new function)', explode(':', $Ex->getMessage(), 2)[0]);
        }
    }

    public function test_evalsha() {
        $Redis = static::$Redis;

        $script = "return {KEYS[1],KEYS[2],ARGV[1],ARGV[2]}";

        try {
            $Redis->evalsha(sha1($script));
            $this->assertTrue(false);
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('NOSCRIPT No matching script. Please use EVAL.', $Ex->getMessage());
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
        } catch (ErrorResponseException $Ex) {
            $this->assertSame('NOTBUSY No scripts in execution right now.', $Ex->getMessage());
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
