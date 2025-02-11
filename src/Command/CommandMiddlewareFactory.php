<?php

declare(strict_types=1);

namespace MySchema\Command;

use Psr\Container\ContainerInterface;

final class CommandMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): CommandMiddleware
    {
        $config = $container->get('config')['commands'];
        return new CommandMiddleware($container, $config);
    }
}
