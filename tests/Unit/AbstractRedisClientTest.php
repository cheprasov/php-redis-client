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
namespace Test\Unit;

use RedisClient\Client\AbstractRedisClient;

/**
 * @see AbstractRedisClient
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

}
