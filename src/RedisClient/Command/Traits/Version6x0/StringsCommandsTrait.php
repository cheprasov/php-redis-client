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
namespace RedisClient\Command\Traits\Version6x0;

use RedisClient\Command\Traits\Version3x2\StringsCommandsTrait as stringsCommandsTraitVersion3x2;

/**
 * Scripting Commands
 * @link http://redis.io/commands#strings
 */
trait StringsCommandsTrait {

    use StringsCommandsTraitVersion3x2;

    /**
     * STRALGO LCS algo-specific-argument [algo-specific-argument ...]
     * @link https://redis.io/commands/stralgo
     *
     * @param array|array[] $algoSpecificArguments
     * @return string|integer|string[]
     */
    public function stralgoLcs(array $algoSpecificArguments) {
        return $this->returnCommand(['STRALGO', 'LCS'], null, $algoSpecificArguments);
    }

}
