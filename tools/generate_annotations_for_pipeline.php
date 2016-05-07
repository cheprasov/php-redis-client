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

const EOL = "\n";

if ($argc === 2 && $argv[1] === '--help') {
    echo '---'. EOL,
        'Tools for generations annotations for Pipelines by versions'. EOL,
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
            preg_replace("/\\s*\n\\s*/", ' ', trim($mf[2]))
        ];
    }
}

//Version3x0/SortedSetsCommandsTrait.php:    public function zrange($key, $start, $stop, $withscores = false)
//if (!preg_match_all('/Version(\d+x\d+)\/(\w+)CommandsTrait\.php.+public function (.+)\((.*)\)/im', $lines, $matches, PREG_SET_ORDER)) {
//    echo 'Not found'. EOL;
//}

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

if (in_array('update', $argv)) {

    $text = '';
    foreach ($versions as $version => $groups) {
        $annotations = [''];
        $annotations[] = 'Redis version ' . ($ver = str_replace('x', '.', $version));
        foreach ($groups as $group => $commands) {
            $annotations[] = '';
            $annotations[] = $group;
            foreach ($commands as $command => $params) {
                // skip for Pipeline
                if (in_array($command, ['subscribe', 'psubscribe', 'monitor'])) {
                    continue;
                }

                if (isset($reservedMethods[$command])) {
                    $annotations[] = '@method $this ' . $reservedMethods[$command] . '(' . $params . ')';
                    $annotations[] = '@method $this ' . $command . '(' . $params . ') - alias method for reversed word <'. $reservedMethods[$command] .'>';
                } else {
                    $annotations[] = '@method $this ' . $command . '(' . $params . ')';
                }
                // deactivation old version of commands
                $text = str_replace('@method $this ' . $command . '(', '-method $this ' . $command . '(', $text);
            }
        }
        $annotations[] = '';
        $text .= implode("\n * ", $annotations);
        $text = str_replace("\n * \n", "\n *\n", $text);

        if (!file_exists($file = './src/RedisClient/Pipeline/Version/Pipeline'.$version.'.php')) {
            continue;
        }

        $old = file_get_contents($file);
        $new = preg_replace('/(?<=\/\*\*)\n (.+\n)+(?= \*\/\nclass Pipeline'.$version.')/', ($text).EOL, $old);
        if ($new) {
            if (in_array('backup', $argv)) {
                copy($file, $back = __DIR__ . '/back/Pipeline' . $version . '.php.' . date('Ymd.His'));
            }
            $new = str_replace('method $this ', 'method Pipeline'.$version.' ', $new);
            if ($new !== $old) {
                file_put_contents($file, $new);
                echo 'File '. $file .' - updated'. EOL;
            } else {
                echo 'File '. $file .' - has not changes'. EOL;
            }
        }
    }

} else {

    $annotations = [];
    foreach ($versions as $version => $groups) {
        $annotations[] = '';
        $annotations[] = 'Redis version ' . str_replace('x', '.', $version);
        foreach ($groups as $group => $commands) {
            $annotations[] = '';
            $annotations[] = $group;
            foreach ($commands as $command => $params) {
                $annotations[] = '@method $this ' . $command . '(' . $params . ')';
            }
        }

    }
    $annotations[] = '';
    echo "/**" . implode("\n * ", $annotations) . "\n */";
}
