<?php

spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class);
    $path = './src/';
    if (is_file($file = $path . $class . '.php')) {
        return include $file;
    }
});

