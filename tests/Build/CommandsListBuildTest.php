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

class CommandsListBuildTest extends TestCase {

    public function test_generate_commands_list() {
        chdir(__DIR__.'/../../');
        $result = `php ./tools/generate_commands_md.php update`;

        $this->assertSame('done', $result);
    }

}
