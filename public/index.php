<?php

// change dir to project root
chdir(realpath(__DIR__ . '/../'));
$cwd = getcwd();

// autoload classes
require "$cwd/vendor/autoload.php";

// build container
$container = require "$cwd/config/container.php";

// run application
(new MySchema\Server\Runtime\RuntimeDetector($container))
    ->detectRuntime()
    ->run();
