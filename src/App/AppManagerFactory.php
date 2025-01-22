<?php

declare(strict_types=1);

namespace MySchema\App;

use Psr\Container\ContainerInterface;

final class AppManagerFactory
{
    public function __invoke(ContainerInterface $container): AppManager
    {
        $config = $container->get('apps');
        return new AppManager($config);
    }
}
