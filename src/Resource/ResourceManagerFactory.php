<?php

declare(strict_types=1);

namespace MySchema\Resource;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Container\ContainerInterface;
use function getcwd;

final class ResourceManagerFactory
{
    public function __invoke(ContainerInterface $container): ResourceManager
    {
        $config = $container->get('config')['resources'] ?? [];
        $apps = $container->get('apps');
        foreach ($apps as $appConfig) {
            foreach ($appConfig['resources']['templates'] ?? [] as $name => $path) {
                $config['templates'][$name] = $path;
            }

            foreach ($appConfig['resources']['blocks'] ?? [] as $name => $path) {
                $config['blocks'][$name] = $path;
            }

            foreach ($appConfig['resources']['queries'] ?? [] as $name => $path) {
                $config['queries'][$name] = $path;
            }
        }

        $adapter = new LocalFilesystemAdapter(getcwd());
        $filesystem = new Filesystem($adapter);

        return new ResourceManager($filesystem, $config);
    }
}
