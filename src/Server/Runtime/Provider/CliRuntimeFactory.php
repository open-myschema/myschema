<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

use function array_values;

final class CliRuntimeFactory
{
    public function __invoke(ContainerInterface $container): CliRuntime
    {
        $console = new Application('MySchema', '0.0.2');
        $commands = $container->get('config')['commands'] ?? [];
        foreach (array_values($commands) as $command) {
            $console->add($container->get($command));
        }

        return new CliRuntime($console);
    }
}
