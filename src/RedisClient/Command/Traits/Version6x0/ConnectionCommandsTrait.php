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

use RedisClient\Command\Traits\Version5x0\ConnectionCommandsTrait as ConnectionCommandsTraitVersion5x0;

/**
 * Connection Commands
 * @link http://redis.io/commands#connection
 */
trait ConnectionCommandsTrait {

    use ConnectionCommandsTraitVersion5x0;

    /**
     * CLIENT CACHING YES|NO
     * @link https://redis.io/commands/client-caching
     *
     * @param bool $isEnabled
     * @return bool
     */
    public function clientCaching($isEnabled) {
        return $this->returnCommand(['CLIENT', 'CACHING'], null, [$isEnabled ? 'YES' : 'NO']);
    }

    /**
     * CLIENT GETREDIR
     * @link https://redis.io/commands/client-getredir
     *
     * @return int
     */
    public function clientCetredir() {
        return $this->returnCommand(['CLIENT', 'GETREDIR'], null, null);
    }

    /**
     * CLIENT TRACKING ON|OFF [REDIRECT client-id] [PREFIX prefix [PREFIX prefix ...]] [BCAST] [OPTIN] [OPTOUT] [NOLOOP]
     * @link https://redis.io/commands/client-tracking
     *
     * @param bool $isEnabled
     * @param int|null $redirectClientId
     * @param bool|null $bcast
     * @param bool|null $optin
     * @param bool|null $optout
     * @param bool|null $noloop
     * @param string|string[]|null $prefixes
     * @return int
     */
    public function clientTracking($isEnabled, $redirectClientId = null, $prefixes = null, $bcast = null, $optin = null, $optout = null, $noloop = null) {
        $params = [$isEnabled ? 'YES' : 'NO'];
        if (isset($redirectClientId)) {
            $params[] = ['REDIRECT', $redirectClientId];
        }
        if (isset($prefixes)) {
            foreach ($prefixes as $prefix) {
                $params[] = ['PREFIX', $prefix];
            }
        }
        if ($bcast) {
            $params[] = 'BCAST';
        }
        if ($optin) {
            $params[] = 'OPTIN';
        }
        if ($optout) {
            $params[] = 'OPTOUT';
        }
        if ($noloop) {
            $params[] = 'NOLOOP';
        }

        return $this->returnCommand(['CLIENT', 'TRACKING'], null, $params);
    }


    /**
     * HELLO protover [AUTH username password] [SETNAME clientname]
     * @link https://redis.io/commands/hello
     *
     * @param int $protover
     * @param string|null username
     * @param string|null password
     * @param string|null $clientName
     * @return int
     */
    public function hello($protover, $username = null, $password = null, $clientName = null) {
        $params = [$protover];

        if ($username && $password) {
            $params[] = ['AUTH', $username, $password];
        }

        if (isset($clientName)) {
            $params[] = ['SETNAME', $clientName];
        }

        return $this->returnCommand(['HELLO'], null, $params);
    }

}
