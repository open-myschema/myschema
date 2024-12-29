<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

final class CliRuntimeFactory
{
    public function __invoke(ContainerInterface $container): CliRuntime
    {
        $console = new Application('MySchema', '0.0.1');
        $commands = $container->get('config')['console']['commands'] ?? [];
        foreach ($commands as $command) {
            $console->add($container->get($command));
        }

        return new CliRuntime($console);
    }
}
