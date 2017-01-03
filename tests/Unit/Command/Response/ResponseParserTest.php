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
namespace Test\Unit\Command\Response;

use RedisClient\Client\AbstractRedisClient;
use RedisClient\Command\Response\ResponseParser;

/**
 * @see ResponseParser
 */
class ResponseParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractRedisClient
     */
    protected function getAbstractRedisClientMock() {
        $Mock = $this->getMockForAbstractClass(AbstractRedisClient::class);
        return $Mock;
    }

    /**
     * @see ResponseParser::parseClientList
     */
    public function test_parseClientList() {
        $data =   'id=1743 addr=127.0.0.1:48446 fd=5 name= age=25550 idle=0 flags=N db=0 sub=0 psub=0 multi=-1 qbuf=0 qbuf-free=32768 obl=0 oll=0 omem=0 events=r cmd=client'
            ."\n".'id=2743 addr=127.0.0.1:48447 fd=5 name= age=25550 idle=0 flags=N db=0 sub=0 psub=0 multi=-1 qbuf=0 qbuf-free=32768 obl=0 oll=0 omem=0 events=r cmd=client'
            ."\n".'id=3743 addr=127.0.0.1:48448 fd=5 name= age=25550 idle=0 flags=N db=0 sub=0 psub=0 multi=-1 qbuf=0 qbuf-free=32768 obl=0 oll=0 omem=0 events=r cmd=client';

        $result = ResponseParser::parseClientList($data);
        $this->assertSame(3, count($result));

        $this->assertSame('1743', $result[0]['id']);
        $this->assertSame('2743', $result[1]['id']);
        $this->assertSame('3743', $result[2]['id']);
        $this->assertSame('127.0.0.1:48446', $result[0]['addr']);
        $this->assertSame('127.0.0.1:48447', $result[1]['addr']);
        $this->assertSame('127.0.0.1:48448', $result[2]['addr']);
        $this->assertSame('', $result[0]['name']);
        $this->assertSame('0', $result[1]['idle']);
        $this->assertSame('N', $result[2]['flags']);
        $this->assertSame('0', $result[0]['db']);
        $this->assertSame('0', $result[1]['sub']);
        $this->assertSame('client', $result[2]['cmd']);
    }

    /**
     * @see ResponseParser::parseGeoArray
     */
    public function test_parseGeoArray() {
        $geodata = [
            ["Palermo","190.4424",["13.361389338970184","38.115556395496299"]],
            ["Catania","56.4413",["15.087267458438873","37.50266842333162"]],
        ];
        $result = ResponseParser::parseGeoArray($geodata);
        $this->assertSame(2, count($result));
        $this->assertSame(["190.4424",["13.361389338970184","38.115556395496299"]], $result['Palermo']);
        $this->assertSame(["56.4413",["15.087267458438873","37.50266842333162"]], $result['Catania']);
        $this->assertSame([], ResponseParser::parseGeoArray([]));

        $geodata = [
            ["Palermo","190.4424"],
            ["Catania","56.4413"],
        ];
        $result = ResponseParser::parseGeoArray($geodata);
        $this->assertSame(2, count($result));
        $this->assertSame(["190.4424"], $result['Palermo']);
        $this->assertSame(["56.4413"], $result['Catania']);
        $this->assertSame([], ResponseParser::parseGeoArray([]));
    }

    /**
     * @see ResponseParser::parseInfo
     */
    public function test_parseInfo() {
        $data = "
# Server
redis_version:3.0.3
redis_git_sha1:00000000
redis_git_dirty:0
redis_build_id:58013d157c63182b
redis_mode:standalone
os:Linux 4.2.0-16-generic x86_64
arch_bits:64
multiplexing_api:epoll
gcc_version:5.2.1
process_id:8799
run_id:abf5908c0edb8e427051ab5375c8428611ee2ebb
tcp_port:6379
uptime_in_seconds:2773598
uptime_in_days:32
hz:10
lru_clock:10754763
config_file:/etc/redis/redis.conf

# Clients
connected_clients:1
client_longest_output_list:0
client_biggest_input_buf:0
blocked_clients:0

# Memory
used_memory:519176
used_memory_human:507.01K
used_memory_rss:3842048
used_memory_peak:11013344
used_memory_peak_human:10.50M
used_memory_lua:36864
mem_fragmentation_ratio:7.40
mem_allocator:jemalloc-3.6.0
";

        $result = ResponseParser::parseInfo($data);
        $this->assertSame(3, count($result));
        $this->assertSame(true, isset($result['Server']));
        $this->assertSame(true, isset($result['Clients']));
        $this->assertSame(true, isset($result['Memory']));

        $this->assertSame('3.0.3', $result['Server']['redis_version']);
        $this->assertSame('1', $result['Clients']['connected_clients']);
        $this->assertSame('519176', $result['Memory']['used_memory']);

        $data = "

# Server
redis_version:3.0.3
redis_git_sha1:00000000
redis_git_dirty:0
redis_build_id:58013d157c63182b
redis_mode:standalone
os:Linux 4.2.0-16-generic x86_64
arch_bits:64
multiplexing_api:epoll
gcc_version:5.2.1
process_id:8799
run_id:abf5908c0edb8e427051ab5375c8428611ee2ebb
tcp_port:6379
uptime_in_seconds:2773598
uptime_in_days:32
hz:10
lru_clock:10754763
config_file:/etc/redis/redis.conf

";

        $result = ResponseParser::parseInfo($data);
        $this->assertSame(17, count($result));
        $this->assertSame('3.0.3', $result['redis_version']);
        $this->assertSame('00000000', $result['redis_git_sha1']);
        $this->assertSame('64', $result['arch_bits']);
        $this->assertSame('5.2.1', $result['gcc_version']);
        $this->assertSame('/etc/redis/redis.conf', $result['config_file']);
    }

    /**
     * @see ResponseParser::parseTime
     */
    public function test_parseTime() {
        $this->assertSame('1453598038.146292', ResponseParser::parseTime(['1453598038','146292']));
        $this->assertSame('1453598038.000292', ResponseParser::parseTime(['1453598038','292']));
        $this->assertSame('1453598038.000000', ResponseParser::parseTime(['1453598038','0']));
        $this->assertSame('1453598038.000010', ResponseParser::parseTime(['1453598038','10']));
        $this->assertSame('1453598038.000707', ResponseParser::parseTime(['1453598038','0707']));
    }

    /**
     * @see ResponseParser::parseAssocArray
     */
    public function test_parseAssocArray() {
        $this->assertSame(['foo' => 'bar'], ResponseParser::parseAssocArray(['foo', 'bar']));
        $this->assertSame(['a' => 'b', 'c' => 'd'], ResponseParser::parseAssocArray(['a', 'b', 'c', 'd']));
        $this->assertSame(['a' => 'd'], ResponseParser::parseAssocArray(['a', 'b', 'a', 'd']));
        $this->assertSame([], ResponseParser::parseAssocArray([]));
    }

    public function provider_parseClusterSlots() {
        return [
            [
                array (
                    0 => array (
                        0 => 0,
                        1 => 5460,
                        2 => array (
                            0 => '127.0.0.1',
                            1 => 7001,
                            2 => '8bdd2e92cfc4ed897a4845f98302138e26b6bff7',
                        ),
                        3 => array (
                            0 => '127.0.0.1',
                            1 => 7004,
                            2 => '841860b9f02092598b73e39f70be9fb5d4c26157',
                        ),
                    ),
                    1 => array (
                        0 => 5461,
                        1 => 10922,
                        2 => array (
                            0 => '127.0.0.1',
                            1 => 7002,
                            2 => 'cb9cd68e912b5dc9d824ecb79dcb8a5ae3ef6418',
                        ),
                        3 => array (
                            0 => '127.0.0.1',
                            1 => 7005,
                            2 => 'ecd0f111c58b58fed56b55e4889a152920ea6632',
                        ),
                    ),
                    2 => array (
                        0 => 10923,
                        1 => 16383,
                        2 => array (
                            0 => '127.0.0.1',
                            1 => 7003,
                            2 => '79074cf03cfb38c5846451531ce5e6fb369eefbf',
                        ),
                        3 => array (
                            0 => '127.0.0.1',
                            1 => 7006,
                            2 => 'c5ce625184dff5e1f6f44e172ce444a9ca691f19',
                        ),
                    ),
                ),
                [
                    5460 => '127.0.0.1:7001',
                    10922 => '127.0.0.1:7002',
                    16383 => '127.0.0.1:7003',
                ]
            ],
            [
                array (
                    0 => array (
                        0 => 0,
                        1 => 10,
                        2 => array (
                            0 => '127.0.0.1',
                            1 => 11,
                        ),
                    ),
                    1 => array (
                        0 => 21,
                        1 => 30,
                        2 => array (
                            0 => '127.0.0.3',
                            1 => 33,
                        ),
                    ),
                    2 => array (
                        0 => 11,
                        1 => 20,
                        2 => array (
                            0 => '127.0.0.2',
                            1 => 22,
                        ),
                    ),
                ),
                [
                    10 => '127.0.0.1:11',
                    20 => '127.0.0.2:22',
                    30 => '127.0.0.3:33',
                ]
            ],
        ];
    }

    /**
     * @see ResponseParser::parseClusterSlots
     * @dataProvider provider_parseClusterSlots
     * @param array $data
     * @param array $expect
     */
    public function test_parseClusterSlots($data, $expect) {
        $this->assertSame($expect, ResponseParser::parseClusterSlots($data));
    }

}
