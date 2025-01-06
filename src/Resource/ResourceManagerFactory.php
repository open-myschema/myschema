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
        $adapter = new LocalFilesystemAdapter(getcwd());
        $filesystem = new Filesystem($adapter);

        return new ResourceManager($filesystem, $config);
    }
}
