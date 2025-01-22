<?php

declare(strict_types=1);

namespace MySchema\Resource;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Container\ContainerInterface;

final class ResourceManagerFactory
{
    public function __invoke(ContainerInterface $container): ResourceManager
    {
        $config = $container->get('config')['resources'] ?? [];
        $apps = $container->get('apps');
        foreach ($apps as $appConfig) {
            foreach ($appConfig['resources']['templates'] ?? [] as $name => $templateConfig) {
                $config['templates'][$name] = $templateConfig;
            }

            foreach ($appConfig['resources']['blocks'] ?? [] as $name => $blockConfig) {
                $config['blocks'][$name] = $blockConfig;
            }

            foreach ($appConfig['resources']['queries'] ?? [] as $name => $queryConfig) {
                $config['queries'][$name] = $queryConfig;
            }
        }

        $adapter = new LocalFilesystemAdapter(\getcwd());
        $filesystem = new Filesystem($adapter);

        return new ResourceManager($filesystem, $config);
    }
}
