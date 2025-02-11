<?php

declare(strict_types= 1);

namespace MySchema\Command;

use Psr\Container\ContainerInterface;
use InvalidArgumentException;
use Throwable;

use function array_values;
use function get_debug_type;
use function in_array;
use function sprintf;

final class CommandFactory
{
    public function __invoke(ContainerInterface $container, $requestedName): object
    {
        $commands = $container->get('config')['commands'] ?? [];
        if (! in_array($requestedName, array_values($commands), true)) {
            throw new InvalidArgumentException(sprintf(
                "Invalid factory %s for requested class %s",
                self::class,
                $requestedName
            ));
        }

        $commandName = null;
        foreach ($commands as $name => $command) {
            if ($command === $requestedName) {
                $commandName = $name;
            }
        }
        try {
            $command = new $requestedName($container, $commandName);
            if (! $command instanceof BaseCommand) {
                throw new InvalidArgumentException(sprintf(
                    "Expected command instance to extend %s, given %s instead",
                    BaseCommand::class,
                    get_debug_type($command)
                ));
            }
        } catch (Throwable $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return $command;
    }
}
