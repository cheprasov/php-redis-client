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
namespace Test\Integration\Version6x0;

use RedisClient\Exception\ErrorResponseException;

include_once(__DIR__. '/../Version5x0/ListsCommandsTest.php');

class ListsCommandsTest extends \Test\Integration\Version5x0\ListsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version6x0\ListsCommandsTrait::lpos
     */
    public function test_lpos() {
        // todo: add test when it is stable
        $this->markTestSkipped('add test when it is stable');
    }


}
