<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// @todo cache config

$config = new ConfigAggregator([
    \Laminas\Diactoros\ConfigProvider::class,
    \MySchema\Server\ConfigProvider::class,
    \MySchema\Application\ConfigProvider::class,
    new PhpFileProvider(realpath(__DIR__) . '/*{local.php}'),
]);

return $config->getMergedConfig();
