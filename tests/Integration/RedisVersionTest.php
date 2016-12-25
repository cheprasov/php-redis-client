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
namespace Test\Integration;

use RedisClient\ClientFactory;

/**
 * Check Redis Versions
 */
class RedisVersionTest extends \PHPUnit_Framework_TestCase {

    // <server>, <client version>, <expected server version>
    protected $versions = [
        [TEST_REDIS_SERVER_2x6_1, '2.6', '2.6.x'],
        [TEST_REDIS_SERVER_2x6_2, '2.6', '2.6.x'],
        [TEST_REDIS_SERVER_2x8_1, '2.8', '2.8.x'],
        [TEST_REDIS_SERVER_2x8_2, '2.8', '2.8.x'],
        [TEST_REDIS_SERVER_3x0_1, '3.0', '3.0.x'],
        [TEST_REDIS_SERVER_3x0_2, '3.0', '3.0.x'],
        [TEST_REDIS_SERVER_3x2_1, '3.2', '3.2.x'],
        [TEST_REDIS_SERVER_3x2_2, '3.2', '3.2.x'],
        // only for stable versions
        //[TEST_REDIS_SERVER_4x0_1, '4.0', '4.0.x'],
        //[TEST_REDIS_SERVER_4x0_2, '4.0', '4.0.x'],
    ];

    /**
     *
     */
    public function test_RedisVersions() {
        foreach ($this->versions as $n => $arr) {
            list($server, $clientVersion, $serverVersion) = $arr;

            $Redis = ClientFactory::create([
                'server' => $server,
                'timeout' => 2,
                'version' => $clientVersion,
                'password' => $n % 2 ? TEST_REDIS_SERVER_PASSWORD : null,
            ]);

            $this->assertSame($clientVersion, $Redis->getSupportedVersion());

            $redisVersion = $Redis->info('Server')['redis_version'];
            $expectedVersion = $this->getRegExp($serverVersion);

            try {
                $this->assertSame(1, preg_match($expectedVersion, $redisVersion));
            } catch (\Exception $Ex) {
                throw new \Exception(sprintf('Incorrect Redis Server Version for [%s], expected: %s, given: %s', $server, $serverVersion, $redisVersion));
            }
        }
    }

    /**
     * @param string $version
     * @return string
     */
    protected function getRegExp($version) {
        $result = str_replace(
            ['.' , 'x'],
            ['\.', '\d+'],
            $version
        );
        return '/^'. $result .'$/';
    }

}
