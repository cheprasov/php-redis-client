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

const EOL = "\n";

if ($argc === 2 && $argv[1] === '--help') {
    echo '---'. EOL,
        'Tools for generation list commands by versions'. EOL,
        'Using: php ./tools/'. basename(__FILE__) .' [update [backup]]'. EOL,
         '---'. EOL;
    exit;
}

$lines = `grep -r 'public function' ./src/RedisClient/Command/Traits/`;
$lines = explode("\n", $lines);
$files = [];

foreach ($lines as $line) {
    $l = explode(':', $line, 2);
    if (!$l[0]) {
        continue;
    }
    if (!preg_match('/Version(\d+x\d+)\/(\w+)CommandsTrait\.php/i', $l[0])) {
        continue;
    }
    $files[] = $l[0];
}
$files = array_unique($files);
$matches = [];

foreach ($files as $file) {
    if (!preg_match('/Version(\d+x\d+)\/(\w+)CommandsTrait\.php/i', $file, $mfile)) {
        continue;
    }

    $f = file_get_contents($file);

    if (!preg_match_all('/public function (.+)\s*\(([^)]*)\)/im', $f, $mfunc, PREG_SET_ORDER)) {
        continue;
    }

    foreach ($mfunc as $mf) {
        $matches[] = [
            $mfile[0],
            $mfile[1],
            $mfile[2],
            $mf[1],
            preg_replace("/\\s*\n\\s*/", ' ', trim($mf[2])),
        ];
    }
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

$reservedMethods = [
    'evalScript' => 'eval',
    'echoMessage' => 'echo',
];

$annotations = [];
foreach ($versions as $version => $groups) {
    $annotations[] = '## Redis version ' . str_replace('x', '.', $version);
    foreach ($groups as $group => $commands) {
        $annotations[] = '';
        $annotations[] = '### ' . $group;
        foreach ($commands as $command => $params) {
            // skip for Pipeline
            if (in_array($command, ['subscribe', 'psubscribe', 'monitor'])) {
                continue;
            }

            $link = "https://github.com/cheprasov/php-redis-client/blob/master/src/RedisClient/Command/Traits/Version{$version}/{$group}CommandsTrait.php";

            if (isset($reservedMethods[$command])) {
                $annotations[] = "- [$reservedMethods[$command]({$params})]({$link})";
                $annotations[] = "- [{$command}({$params})]({$link}) - alias method for `{$reservedMethods[$command]}`";
            } else {
                $annotations[] = "- [{$command}({$params})]({$link})";
            }
        }
    }

}
$annotations[] = '';
$text =  "# Redis Commands\n\n"
    . implode("\n", $annotations)
    . "\n";

if (in_array('update', $argv)) {
    $file = __DIR__ . '/../COMMANDS.md';
    file_put_contents($file, $text);
    echo 'done';
} else {
    echo $text;
}


