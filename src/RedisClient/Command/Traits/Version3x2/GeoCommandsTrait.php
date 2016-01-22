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
namespace RedisClient\Command\Traits\Version3x2;

use RedisClient\Command\Response\ResponseParser;

trait GeoCommandsTrait {

    /**
     * GEOADD key longitude latitude member [longitude latitude member ...]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/geoadd
     *
     * @param string $key
     * @param array $members [member => [longitude, latitude]]
     * @return int The number of elements added to the sorted set,
     * not including elements already existing for which the score was updated.
     */
    public function geoadd($key, array $members) {
        $params = [$key];
        foreach ($members as $member => $degrees) {
            $params[] = $degrees[0];
            $params[] = $degrees[1];
            $params[] = $member;
        }
        return $this->returnCommand(['GEOADD'], $params);
    }

    /**
     * GEODIST key member1 member2 [unit]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N))
     * @link http://redis.io/commands/geodist
     *
     * @param string $key
     * @param string $member1
     * @param string $member2
     * @param string|null $unit
     * @return string The command returns the distance as a double (represented as a string)
     * in the specified unit, or NULL if one or both the elements are missing.
     */
    public function geodist($key, $member1, $member2, $unit = null) {
        $params = [$key, $member1, $member2];
        if (isset($unit)) {
            $params[] = $unit;
        }
        return $this->returnCommand(['GEODIST'], $params);
    }

    /**
     * GEOHASH key member [member ...]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N)) for each member requested, where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/geohash
     *
     * @param string $key
     * @param string|string[] $members
     * @return string[] The command returns an array where each element is
     * the Geohash corresponding to each member name passed as argument to the command.
     */
    public function geohash($key, $members) {
        return $this->returnCommand(['GEOHASH'], [$key, (array) $members]);
    }

    /**
     * GEOPOS key member [member ...]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N)) for each member requested, where N is the number of elements in the sorted set.
     * @link http://redis.io/commands/geopos
     *
     * @param string $key
     * @param string|string[] $members
     * @return string[] The command returns an array where each element is a two elements array
     * representing longitude and latitude (x,y) of each member name passed as argument to the command.
     * Non existing elements are reported as NULL elements of the array.
     */
    public function geopos($key, $members) {
        return $this->returnCommand(['GEOPOS'], [$key, (array) $members]);
    }

    /**
     * GEORADIUS key longitude latitude radius m|km|ft|mi [WITHCOORD] [WITHDIST] [WITHHASH] [COUNT count]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(N+log(M)) where N is the number of elements inside the bounding box of
     * the circular area delimited by center and radius and M is the number of items inside the index.
     * @link http://redis.io/commands/georadius
     *
     * @param string $key
     * @param string $longitude
     * @param string $latitude
     * @param string $radius
     * @param string $unit
     * @param bool|false $withcoord
     * @param bool|false $withdist
     * @param bool|false $withhash
     * @param int|null $count
     * @param bool|null $asc (true => ASC, false => DESC)
     * @return array
     */
    public function georadius($key, $longitude, $latitude, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null) {
        $params = [$key, $longitude, $latitude, $radius, $unit];
        $parse = false;
        if ($withcoord) {
            $params[] = 'WITHCOORD';
            $parse = true;
        }
        if ($withdist) {
            $params[] = 'WITHDIST';
            $parse = true;
        }
        if ($withhash) {
            $params[] = 'WITHHASH';
            $parse = true;
        }
        if ($count) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        if (isset($asc)) {
            $params[] = $asc ? 'ASC' : 'DESC';
        }
        return $this->returnCommand(['GEORADIUS'], $params, $parse ? ResponseParser::PARSE_GEO_ARRAY : null);
    }

    /**
     * GEORADIUSBYMEMBER key member radius m|km|ft|mi [WITHCOORD] [WITHDIST] [WITHHASH] [COUNT count]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(N+log(M)) where N is the number of elements inside the bounding box of
     * the circular area delimited by center and radius and M is the number of items inside the index.
     * @link http://redis.io/commands/georadiusbymember
     *
     * @param string $key
     * @param string $member
     * @param string $radius
     * @param string $unit
     * @param bool|false $withcoord
     * @param bool|false $withdist
     * @param bool|false $withhash
     * @param int|null $count
     * @param bool|null $asc (true => ASC, false => DESC)
     * @return array
     */
    public function georadiusbymember($key, $member, $radius, $unit, $withcoord = false, $withdist = false, $withhash = false, $count = null, $asc = null) {
        $params = [$key, $member, $radius, $unit];
        $parse = false;
        if ($withcoord) {
            $params[] = 'WITHCOORD';
            $parse = true;
        }
        if ($withdist) {
            $params[] = 'WITHDIST';
            $parse = true;
        }
        if ($withhash) {
            $params[] = 'WITHHASH';
            $parse = true;
        }
        if ($count) {
            $params[] = 'COUNT';
            $params[] = $count;
        }
        if (isset($asc)) {
            $params[] = $asc ? 'ASC' : 'DESC';
        }
        return $this->returnCommand(['GEORADIUSBYMEMBER'], $params, $parse ? ResponseParser::PARSE_GEO_ARRAY : null);
    }

}
