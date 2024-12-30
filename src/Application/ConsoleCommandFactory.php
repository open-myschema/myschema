<?php

declare(strict_types= 1);

namespace MySchema\Application;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

final class ConsoleCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        $commands = $container->get('config')['console']['commands'] ?? [];
        if (! \in_array($requestedName, $commands, true)) {
            throw new \InvalidArgumentException(\sprintf(
                "Invalid factory %s for requested class %s",
                self::class,
                $requestedName
            ));
        }

        return new $requestedName($container);
    }
}
