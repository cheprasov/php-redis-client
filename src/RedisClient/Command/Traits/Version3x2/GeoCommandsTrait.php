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

use RedisClient\Command\Parameter\Parameter;

trait GeoCommandsTrait {

    /**
     * GEOADD key longitude latitude member [longitude latitude member ...]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N)) for each item added, where N is the number of elements in the sorted set.
     *
     * @param $key
     * @param array $members [member => [longitude, latitude]]
     * @return int The number of elements added to the sorted set,
     * not including elements already existing for which the score was updated.
     */
    public function geoadd($key, array $members) {
        $params = [
            Parameter::key($key)
        ];
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
     *
     * @param string $key
     * @param string $member1
     * @param string $member2
     * @param string|null $unit
     * @return string The command returns the distance as a double (represented as a string)
     * in the specified unit, or NULL if one or both the elements are missing.
     */
    public function geodist($key, $member1, $member2, $unit = null) {
        $params = [
            Parameter::key($key),
            Parameter::key($member1),
            Parameter::key($member2),
        ];
        if (isset($unit)) {
            $params[] = Parameter::geoUnit($unit);
        }
        return $this->returnCommand(['GEODIST'], $params);
    }

    /**
     * GEOHASH key member [member ...]
     * Beta Not yet available in a stable version of Redis. Download unstable if you want to test this command.
     * Time complexity: O(log(N)) for each member requested, where N is the number of elements in the sorted set.
     *
     * @param string $key
     * @param string|string[] $members
     * @return string[] The command returns an array where each element is
     * the Geohash corresponding to each member name passed as argument to the command.
     */
    public function geohash($key, $members) {
        return $this->returnCommand(['GEOHASH'], [
            Parameter::key($key),
            Parameter::keys($members),
        ]);
    }
}
