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
namespace Test\Build;

use PHPUnit\Framework\TestCase;
use RedisClient\Client\AbstractRedisClient;

class VersionTest extends TestCase {

    public function test_version() {
        chdir(__DIR__.'/../../');
        $composer = json_decode(file_get_contents('./composer.json'), true);

        $this->assertSame(true, isset($composer['version']));
        $this->assertSame(
            AbstractRedisClient::VERSION,
            $composer['version'],
            'Please, change version in composer.json'
        );

        $readme = file('./README.md');
        $this->assertSame(
            true,
            strpos($readme[3], 'RedisClient v'.$composer['version']) > 0,
            'Please, change version in README.md'
        );

        $readme = file('./CHANGELOG.md');
        $this->assertSame(
            true,
            strpos($readme[2], '### v'.$composer['version']) === 0,
            'Please, add new version to CHANGELOG.md'
        );
    }

}
