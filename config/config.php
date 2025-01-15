<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// @todo cache config

$config = new ConfigAggregator([
    \Laminas\Diactoros\ConfigProvider::class,
    \Laminas\InputFilter\ConfigProvider::class,
    \Laminas\Validator\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \MySchema\Server\ConfigProvider::class,
    \MySchema\Action\ConfigProvider::class,
    \MySchema\Database\ConfigProvider::class,
    \MySchema\Platform\ConfigProvider::class,
    \MySchema\Resource\ConfigProvider::class,
    \MySchema\Security\ConfigProvider::class,
    \MySchema\Content\ConfigProvider::class,
    \MySchema\PyServer\ConfigProvider::class,
    \MySchema\Helper\ConfigProvider::class,
    new PhpFileProvider(realpath(__DIR__) . '/*{local.php}'),
]);

return $config->getMergedConfig();
