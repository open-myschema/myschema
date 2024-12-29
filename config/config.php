<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;

// @todo cache config

$config = new ConfigAggregator([
    \Laminas\Diactoros\ConfigProvider::class,
    \MySchema\Server\ConfigProvider::class,
]);

return $config->getMergedConfig();
