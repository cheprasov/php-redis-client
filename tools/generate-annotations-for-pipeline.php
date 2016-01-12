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

if ($argc === 2 && $argv[1] === '--help') {
    echo '---'. PHP_EOL,
        'Tools for generations annotations for Pipelines by versions'. PHP_EOL,
        'Using: php ./tools/'. basename(__FILE__) .' > /save/to/file/path'. PHP_EOL,
         '---'. PHP_EOL;
    exit;
}

$lines = `grep -r 'function' ./src/RedisClient/Command/Traits/`;

//Version3x0/SortedSetsCommandsTrait.php:    public function zrange($key, $start, $stop, $withscores = false)
if (!preg_match_all('/Version(\d+x\d+)\/(\w+)CommandsTrait\.php.+public function (.+)\((.*)\)/im', $lines, $matches, PREG_SET_ORDER)) {
    echo 'Not found'. PHP_EOL;
}

$versions = [];

foreach ($matches as $m) {
    list(, $version, $group, $command, $params) = $m;

    if (!isset($versions[$version])) {
        $versions[$version] = [];
    }

    if (!isset($versions[$version][$group])) {
        $versions[$version][$group] = [];
    }

    $versions[$version][$group][$command] = $params;
}

ksort($versions);

$annotations = [];
foreach ($versions as $version => $groups) {
    $annotations[] = '';
    $annotations[] = 'Redis version '. str_replace('x', '.', $version);
    foreach ($groups as $group => $commands) {
        $annotations[] = '';
        $annotations[] = $group;
        //$annotations[] = '';
        foreach ($commands as $command => $params) {
            $annotations[] = '@method $this '. $command .'('. $params .')';
        }
    }

}
$annotations[] = '';

echo "/**". implode("\n * ", $annotations) ."\n */";
