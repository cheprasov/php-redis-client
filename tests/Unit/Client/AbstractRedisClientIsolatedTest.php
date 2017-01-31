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

use ExtraMocks\Mocks;
use RedisClient\Client\AbstractRedisClient;
use RedisClient\Exception\MovedResponseException;
use RedisClient\RedisClient;

/**
 * @see AbstractRedisClient
 * @runTestsInSeparateProcesses
 * @runInSeparateProcess
 */
class AbstractRedisClientIsolatedTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->mockStream();
    }

    protected function mockStream() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\stream_socket_client', function($s) {return $s;});
        Mocks::mockGlobalFunction('\RedisClient\Connection\stream_set_timeout', function() {return true;});
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {return $c;});
        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {return '';});
        Mocks::mockGlobalFunction('\RedisClient\Connection\fread', function() {return '';});
        Mocks::mockGlobalFunction('\RedisClient\Connection\fclose', function() {return true;});
    }

    public function test_mockStream() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
            return "+TEST\r\n";
        });

        $Redis = new RedisClient();
        $this->assertSame('TEST', $Redis->ping());
        unset($Redis);
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(0, Mocks::getCountCalls('\RedisClient\Connection\fread'));
        $this->assertSame(0, Mocks::getCountCalls('\RedisClient\Connection\fclose'));
    }

    public function test_MovedErrorResponse() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {
            $this->assertSame('tcp://127.0.0.1:6379', $h);
            $this->assertSame("*2\r\n\$3\r\nGET\r\n\$3\r\nkey\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });
        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
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

        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(0, Mocks::getCountCalls('\RedisClient\Connection\fread'));
    }

    public function test_ClusterEmptyMovedErrorResponse() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {
            static $data = [
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo1\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
            ];
            $datum = array_shift($data);
            $this->assertSame($datum[0], $h);
            $this->assertSame($datum[1], $m);
            return $c;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
            static $data = [
                "-MOVED 12182 127.0.0.3:7003\r\n",
                "\$3\r\n",
                "\$4\r\n",
                "-MOVED 1044 127.0.0.1:7001\r\n",
                "\$4\r\n",
                "\$4\r\n",
            ];
            $datum = array_shift($data);
            return $datum;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fread', function() {
            static $data = [
                "bar\r\n",
                "bar1\r\n",
                "bar2\r\n",
                "bar3\r\n",
            ];
            $datum = array_shift($data);
            return $datum;
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => []
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));
        $this->assertSame('bar1', $Redis->get('foo1'));
        $this->assertSame('bar2', $Redis->get('foo2'));
        $this->assertSame('bar3', $Redis->get('foo2'));

        $this->assertSame(2, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(2, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(6, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(6, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(4, Mocks::getCountCalls('\RedisClient\Connection\fread'));
    }

    public function test_ClusterFullMovedErrorResponse() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {
            $this->assertSame('tcp://127.0.0.3:7003', $h);
            $this->assertSame("*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n", $m);
            $this->assertSame(22, $c);
            return $c;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
            return "\$3\r\n";
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fread', function() {
            return "bar\r\n";
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => [
                    5460  => '127.0.0.1:7001',
                    10922 => '127.0.0.2:7002',
                    16383 => '127.0.0.3:7003',
                ]
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));

        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(1, Mocks::getCountCalls('\RedisClient\Connection\fread'));
    }

    public function test_ClusterFullAskErrorResponse() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {
            static $data = [
                [
                    'tcp://127.0.0.3:7003',
                    "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n",
                ],
                [
                    'tcp://127.0.0.2:7002',
                    "*1\r\n\$6\r\nASKING\r\n",
                ],
                [
                    'tcp://127.0.0.2:7002',
                    "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n",
                ],
                [
                    'tcp://127.0.0.3:7003',
                    "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n",
                ],
            ];
            $datum = array_shift($data);
            $this->assertSame($datum[0], $h);
            $this->assertSame($datum[1], $m);
            return $c;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
            static $data = [
                "-ASK 12182 127.0.0.2:7002\r\n",
                "+OK\r\n",
                "\$3\r\n",
                "\$7\r\n",
            ];
            return array_shift($data);
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fread', function() {
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
                    10922 => '127.0.0.2:7002',
                    16383 => '127.0.0.3:7003',
                ]
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));
        $this->assertSame('bar-bar', $Redis->get('foo'));

        $this->assertSame(2, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(2, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(4, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(4, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(2, Mocks::getCountCalls('\RedisClient\Connection\fread'));
    }

    public function test_ClusterEmptyMixResponse() {
        Mocks::mockGlobalFunction('\RedisClient\Connection\fwrite', function($h, $m, $c) {
            static $data = [
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.2:7002', "*1\r\n\$6\r\nASKING\r\n"],
                ['tcp://127.0.0.2:7002', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$3\r\nfoo\r\n"],
                ['tcp://127.0.0.1:7001', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo2\r\n"],
                ['tcp://127.0.0.3:7003', "*2\r\n\$3\r\nGET\r\n\$4\r\nfoo1\r\n"],
            ];
            $datum = array_shift($data);
            $this->assertSame($datum[0], $h);
            $this->assertSame($datum[1], $m);
            return $c;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fgets', function() {
            static $data = [
                "-MOVED 12182 127.0.0.3:7003\r\n",
                "-ASK 12182 127.0.0.2:7002\r\n",
                "+OK\r\n",
                "\$3\r\n",
                "\$4\r\n",
                "-MOVED 1044 127.0.0.1:7001\r\n",
                "\$4\r\n",
                "\$4\r\n",
                "\$4\r\n",
                "\$4\r\n",
            ];
            $datum = array_shift($data);
            return $datum;
        });

        Mocks::mockGlobalFunction('\RedisClient\Connection\fread', function() {
            static $data = [
                "bar\r\n",
                "bar1\r\n",
                "bar2\r\n",
                "bar3\r\n",
                "bar4\r\n",
                "bar5\r\n",
            ];
            $datum = array_shift($data);
            return $datum;
        });

        $Redis = new RedisClient([
            'server' => '127.0.0.1:7001',
            'cluster' => [
                'enabled' => true,
                'clusters' => []
            ]
        ]);

        $this->assertSame('bar', $Redis->get('foo'));
        $this->assertSame('bar1', $Redis->get('foo'));
        $this->assertSame('bar2', $Redis->get('foo2'));
        $this->assertSame('bar3', $Redis->get('foo'));
        $this->assertSame('bar4', $Redis->get('foo2'));
        $this->assertSame('bar5', $Redis->get('foo1'));

        $this->assertSame(3, Mocks::getCountCalls('\RedisClient\Connection\stream_socket_client'));
        $this->assertSame(3, Mocks::getCountCalls('\RedisClient\Connection\stream_set_timeout'));
        $this->assertSame(10, Mocks::getCountCalls('\RedisClient\Connection\fwrite'));
        $this->assertSame(10, Mocks::getCountCalls('\RedisClient\Connection\fgets'));
        $this->assertSame(6, Mocks::getCountCalls('\RedisClient\Connection\fread'));
    }
}
