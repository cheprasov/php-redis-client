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

use RedisClient\Command\Traits\Version5x0\ServerCommandsTrait as ServerCommandsTraitVersion5x0;

/**
 * Server Commands
 * @link http://redis.io/commands#server
 */
trait ServerCommandsTrait {

    use ServerCommandsTraitVersion5x0;

    /**
     * ACL CAT [categoryname]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-cat
     *
     * @param string|null $categoryName
     * @return string[]
     */
    public function aclCat($categoryName = null) {
        return $this->returnCommand(['ACL', 'CAT'], null, $categoryName ? [$categoryName] : null);
    }

    /**
     * ACL DELUSER username [username ...]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-deluser
     *
     * @param string[] $usernames
     * @return integer
     */
    public function aclDeluser($usernames) {
        $usernames = (array)$usernames;
        return $this->returnCommand(['ACL', 'DELUSER'], null, $usernames);
    }

    /**
     * ACL GENPASS [bits]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-genpass
     *
     * @param integer|null $bits
     * @return string
     */
    public function aclGenpass($bits = null) {
        return $this->returnCommand(['ACL', 'GENPASS'], null, isset($bits) ? [$bits] : null);
    }

    /**
     * ACL GETUSER username
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-getuser
     *
     * @param string $username
     * @return string[]
     */
    public function aclGetuser($username) {
        return $this->returnCommand(['ACL', 'GETUSER'], null, [$username]);
    }

    /**
     * ACL HELP
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-help
     *
     * @return string[]
     */
    public function aclHelp() {
        return $this->returnCommand(['ACL', 'HELP'], null, null);
    }

    /**
     * ACL LIST
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-list
     *
     * @return string[]
     */
    public function aclList() {
        return $this->returnCommand(['ACL', 'LIST'], null, null);
    }

    /**
     * ACL LOG [count or RESET]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-log
     *
     * @param int|null $count
     * @return string[]
     */
    public function aclLog($count = null) {
        return $this->returnCommand(['ACL', 'LOG'], null, isset($count) ? [$count] : null);
    }

    /**
     * ACL LOG [count or RESET]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-log
     *
     * @return bool
     */
    public function aclLogReset() {
        return $this->returnCommand(['ACL', 'LOG'], null, ['RESET']);
    }

    /**
     * ACL SAVE
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-save
     *
     * @return bool
     */
    public function aclSave() {
        return $this->returnCommand(['ACL', 'SAVE'], null, null);
    }

    /**
     * ACL SETUSER username [rule [rule ...]]
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-setuser
     *
     * @return string $username
     * @return string|string[]|null $rules
     * @return bool
     */
    public function aclSetuser($username, $rules = null) {
        $params = [$username];
        if (isset($rules)) {
            $params[] = $rules;
        }
        return $this->returnCommand(['ACL', 'SETUSER'], null, $params);
    }

    /**
     * ACL USERS
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-users
     *
     * @return string[]
     */
    public function aclUsers() {
        return $this->returnCommand(['ACL', 'USERS'], null, null);
    }

    /**
     * ACL WHOAMI
     * Available since 6.0.0.
     * @link https://redis.io/commands/acl-whoami
     *
     * @return string
     */
    public function aclWhoami() {
        return $this->returnCommand(['ACL', 'WHOAMI'], null, null);
    }
}
