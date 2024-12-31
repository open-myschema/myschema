<?php

declare(strict_types=1);

namespace MySchema\Platform;

use Psr\Container\ContainerInterface;

final class PlatformMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): PlatformMiddleware
    {
        return new PlatformMiddleware($container);
    }
}
