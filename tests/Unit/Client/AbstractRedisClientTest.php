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
namespace Test\Unit\Client;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Exception\EmptyResponseException;
use RedisClient\Exception\MovedResponseException;
use RedisClient\RedisClient;
use Test\Unit\GlobalFunctionMock;

/**
 * @see AbstractRedisClient
 * @runTestsInSeparateProcesses
 */
class AbstractRedisClientTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractRedisClient
     */
    protected function getAbstractRedisClientMock() {
        $Mock = $this->getMockForAbstractClass(AbstractRedisClient::class);
        return $Mock;
    }

    public function provider_parseRawString() {
        return [
            [['SET', 'foo', 'bar'],  'SET foo bar'],
            [['SET', 'foo', 'bar'],  '  SET    foo    bar   '],
            [['SET', 'foo', 'hello world'],  'SET foo "hello world"'],
            [['SET', '', 'hello world'],  'SET "" "hello world"'],
            [['SET', 'some key', 'hello world'],  'SET "some key" "hello world"'],
        
            [
                ['SET', 'some "key"', 'hello my "little" world'],
                'SET "some \"key\"" "hello my \"little\" world"'
            ],
            [['SET', "\x0\xFF", ''],  "SET \"\x0\xFF\" \"\""],
            [['SET', "\x0", "\x0"],  "SET \"\x0\" \"\x0\""],

            [['PING'],  'PING'],
            [['PING'],  ' PING '],

            [['DEL', 'foo', 'bar'],  'DEL foo bar'],
            [['DEL', 'foo bar', 'some key'],  'DEL "foo bar" "some key"'],
            [['SET', 'foo bar', "some\r\nkey"],  "SET \"foo bar\" \"some\r\nkey\""],
            [['DEL', 'a', 'b', 'c', 'd', 'e', 'f'],  'DEL a  b   c    d e  f  '],
            [['DEL', '""', '"'],  'DEL "\"\"" "\""'],
            [['DEL', '\"\"', '\"'],  'DEL "\\\"\\\"" "\\\""'],

            [['SET', 'foo-bar', 'some-value'],  'SET foo-bar some-value'],
            [['SET', "foo\x0", 'some-value'],  "SET foo\x0 some-value"],
            [['SET', "\x0", 'some-value'],  "SET \"\x0\" some-value"],
            [['SET', "\x0\x1\x2", "\x0\x1\x2"],  "SET \"\x0\x1\x2\" \"\x0\x1\x2\""],
            [['SET', "\x0 \x1 \x2", "\x0 \x1 \x2"],  "SET \"\x0 \x1 \x2\" \"\x0 \x1 \x2\""],
        ];
    }

    /**
     * @see AbstractRedisClient::parseRawString
     * @dataProvider provider_parseRawString
     * @param string[] $expect
     * @param string $params
     */
    public function test_parseRawString($expect, $params) {
        $Method = new \ReflectionMethod(AbstractRedisClient::class, 'parseRawString');
        $Method->setAccessible(true);
        $Client = $this->getAbstractRedisClientMock();
        $this->assertSame($expect, $Method->invoke($Client, $params));
    }

    public function provider_getStructure() {
        return [
            [['PING'], ['PING'], null],
            [['PING'], ['PING'], []],
            [['SET', 'foo', 'bar'], ['SET'], ['foo', 'bar']],
            [['SET', 'foo', 'bar'], ['SET'], [['foo', 'bar']]],
            [['SET', 'a', 'b', 'c', 'd', 'e'], ['SET'], ['a', ['b', 'c'], 'd', ['e']]],
        ];
    }

    /**
     * @see AbstractRedisClient::getStructure
     * @dataProvider provider_getStructure
     * @param string[] $expect
     * @param string[] $command
     * @param string[]|null $params
     */
    public function test_getStructure($expect, $command, $params) {
        $Method = new \ReflectionMethod(AbstractRedisClient::class, 'getStructure');
        $Method->setAccessible(true);
        $Client = $this->getAbstractRedisClientMock();
        $this->assertSame($expect, $Method->invoke($Client, $command, $params));
    }

    protected function mockStream() {
        include_once(__DIR__ . '/../GlobalFunctionMock.php');
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'stream_socket_client', function() {return true;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'stream_set_timeout', function() {return true;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fwrite', function($h, $m, $c) {return $c;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fgets', function() {return '';});
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fread', function() {return '';});
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fclose', function() {return true;});
    }

    public function test_mockStream() {
        $this->mockStream();
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fgets', function() {
            return "+TEST\r\n";
        });

        $Redis = new RedisClient();
        $this->assertSame('TEST', $Redis->ping());
        unset($Redis);
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('stream_socket_client'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('stream_set_timeout'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('fwrite'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('fgets'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('fread'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('fclose'));
    }

    public function test_stream() {
        $Redis = new RedisClient();
        $this->assertSame('PONG', $Redis->ping());
    }

    public function test_MovedErrorResponse() {
        $this->mockStream();
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fwrite', function($h, $m, $c) {
            $this->assertSame(true, $h);
            $this->assertSame("*2\r\n$3\r\nGET\r\n$3\r\nkey\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });
        GlobalFunctionMock::mockFunction('RedisClient\Connection', 'fgets', function() {
            return "-MOVED 42 server\r\n";
        });

        $Redis = new RedisClient();
        try {
            $Redis->get('key');
            $this->assertTrue(false, 'Expect MovedResponseException');
        } catch (\Exception $Ex) {
            /** @var MovedResponseException $Ex*/
            $this->assertSame(true, $Ex instanceof MovedResponseException);
            $this->assertSame(42, $Ex->getSlot());
            $this->assertSame('server', $Ex->getServer());
        }

        $this->assertSame(1, GlobalFunctionMock::getCountCalls('stream_socket_client'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('stream_set_timeout'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('fwrite'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('fgets'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('fread'));
    }
}
