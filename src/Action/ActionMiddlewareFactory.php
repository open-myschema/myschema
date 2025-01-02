<?php

declare(strict_types=1);

namespace MySchema\Action;

use Psr\Container\ContainerInterface;

final class ActionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ActionMiddleware
    {
        return new ActionMiddleware($container);
    }
}
