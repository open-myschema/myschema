<?php

declare(strict_types=1);

namespace MySchema\Command;

use Psr\Container\ContainerInterface;

final class CommandMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): CommandMiddleware
    {
        $config = $container->get('config')['commands'];
        foreach ($container->get('apps') ?? [] as $appConfig) {
            if (! isset($appConfig['commands'])) {
                continue;
            }

            foreach ($appConfig['commands'] as $name => $class) {
                $config[$name] = $class;
            }
        }
        return new CommandMiddleware($container, $config);
    }
}
