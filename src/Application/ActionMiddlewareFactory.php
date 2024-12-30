<?php

declare(strict_types=1);

namespace MySchema\Application;

use Psr\Container\ContainerInterface;

final class ActionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ActionMiddleware
    {
        return new ActionMiddleware($container);
    }
}
