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
namespace RedisClient\Exception;

class ExceptionFactory {

    /**
     * @param string $message
     * @returns ErrorResponseException|MovedResponseException|AskResponseException
     */
    public static function createResponseExceptionByMessage($message) {
        $type = strstr($message, ' ', true);
        switch ($type) {
            case 'MOVED':
                return new MovedResponseException($message);
            case 'ASK':
                return new AskResponseException($message);
            case 'TRYAGAIN':
                return new TryAgainResponseException($message);
            case 'CROSSSLOT':
                return new CrossSlotResponseException($message);
        }
        return new ErrorResponseException($message);
    }
}
