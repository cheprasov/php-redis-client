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

include_once(__DIR__. '/../Version5x0/StringsCommandsTest.php');

class StringsCommandsTest extends \Test\Integration\Version5x0\StringsCommandsTest {

    /**
     * @see \RedisClient\Command\Traits\Version6x0\StringCommandsTrait::stralgoLcs()
     */
    public function test_stralgoLcs() {
        $Redis = static::$Redis;
        $Redis->mset([
            'key1' => 'ohmytext',
            'key2' => 'mynewtext',
        ]);
        $this->assertSame('mytext', $Redis->stralgoLcs(['KEYS', 'key1', 'key2']));
        $this->assertSame(6, $Redis->stralgoLcs(['STRINGS', 'ohmytext', 'mynewtext', 'LEN']));
    }

}
