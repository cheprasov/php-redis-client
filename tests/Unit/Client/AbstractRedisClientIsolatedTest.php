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

include_once(__DIR__ . '/../GlobalFunctionMock.php');

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Exception\MovedResponseException;
use RedisClient\RedisClient;
use Test\Unit\GlobalFunctionMock;

/**
 * @see AbstractRedisClient
 * @runTestsInSeparateProcesses
 */
class AbstractRedisClientIsolatedTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->mockStream();
    }

    protected function mockStream() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::stream_socket_client', function($s) {return $s;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection::stream_set_timeout', function() {return true;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fwrite', function($h, $m, $c) {return $c;});
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {return '';});
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fread', function() {return '';});
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fclose', function() {return true;});
    }

    public function test_mockStream() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {
            return "+TEST\r\n";
        });

        $Redis = new RedisClient();
        $this->assertSame('TEST', $Redis->ping());
        unset($Redis);
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_socket_client'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_set_timeout'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fwrite'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fgets'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fread'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fclose'));
    }

    public function test_MovedErrorResponse() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fwrite', function($h, $m, $c) {
            $this->assertSame('tcp://127.0.0.1:6379', $h);
            $this->assertSame("*2\r\n$3\r\nGET\r\n$3\r\nkey\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {
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

        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_socket_client'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_set_timeout'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fwrite'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fgets'));
        $this->assertSame(0, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fread'));
    }

    public function test_ClusterEmptyMovedErrorResponse() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fwrite', function($h, $m, $c) {
            static $servers = [
                'tcp://127.0.0.1:7001',
                'tcp://127.0.0.1:7003',
            ];
            $this->assertSame(array_shift($servers), $h);
            $this->assertSame("*2\r\n$3\r\nGET\r\n$3\r\nfoo\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {
            static $data = [
                "-MOVED 12182 127.0.0.1:7003\r\n",
                "\$3\r\n",
            ];
            return array_shift($data);
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fread', function() {
            return "bar\r\n";
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => []
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));

        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_socket_client'));
        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_set_timeout'));
        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fwrite'));
        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fgets'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fread'));
    }

    public function test_ClusterFullMovedErrorResponse() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fwrite', function($h, $m, $c) {
            $this->assertSame('tcp://127.0.0.1:7003', $h);
            $this->assertSame("*2\r\n$3\r\nGET\r\n$3\r\nfoo\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {
            return "\$3\r\n";
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fread', function() {
            return "bar\r\n";
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => [
                    5460  => '127.0.0.1:7001',
                    10922 => '127.0.0.1:7002',
                    16383 => '127.0.0.1:7003',
                ]
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));

        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_socket_client'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_set_timeout'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fwrite'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fgets'));
        $this->assertSame(1, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fread'));
    }

    public function test_ClusterFullAskErrorResponse() {
        GlobalFunctionMock::mockFunction('RedisClient\Connection::fwrite', function($h, $m, $c) {
            static $data = [
                [
                    'tcp://127.0.0.1:7003',
                    "*2\r\n$3\r\nGET\r\n$3\r\nfoo\r\n",
                ],
                [
                    'tcp://127.0.0.1:7002',
                    "*1\r\n$6\r\nASKING\r\n",
                ],
                [
                    'tcp://127.0.0.1:7002',
                    "*2\r\n$3\r\nGET\r\n$3\r\nfoo\r\n",
                ],
                [
                    'tcp://127.0.0.1:7003',
                    "*2\r\n$3\r\nGET\r\n$3\r\nfoo\r\n",
                ],
            ];
            $datum = array_shift($data);
            $this->assertSame($datum[0], $h);
            $this->assertSame($datum[1], $m);
            return $c;
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fgets', function() {
            static $data = [
                "-ASK 12182 127.0.0.1:7002\r\n",
                "+OK\r\n",
                "\$3\r\n",
                "\$7\r\n",
            ];
            return array_shift($data);
        });

        GlobalFunctionMock::mockFunction('RedisClient\Connection::fread', function() {
            static $data = [
                "bar\r\n",
                "bar-bar\r\n"
            ];
            return array_shift($data);
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => [
                    5460  => '127.0.0.1:7001',
                    10922 => '127.0.0.1:7002',
                    16383 => '127.0.0.1:7003',
                ]
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));
        $this->assertSame('bar-bar', $Redis->get('foo'));

        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_socket_client'));
        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::stream_set_timeout'));
        $this->assertSame(4, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fwrite'));
        $this->assertSame(4, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fgets'));
        $this->assertSame(2, GlobalFunctionMock::getCountCalls('RedisClient\Connection::fread'));
    }
}
