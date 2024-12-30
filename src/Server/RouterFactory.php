<?php

declare(strict_types=1);

namespace MySchema\Server;

use Psr\Container\ContainerInterface;

final class RouterFactory
{
    public function __invoke(ContainerInterface $container): Router
    {
        return new Router;
    }
}
