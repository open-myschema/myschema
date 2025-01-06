<?php

declare(strict_types=1);

namespace MySchema\Database;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'console' => $this->getConsoleCommands(),
            'dependencies' => $this->getDependencies(),
            'migrations' => $this->getMigrations(),
        ];
    }

    private function getConsoleCommands(): array
    {
        return [
            'commands' => [
                Migrator\RollBackCommand::class,
                Migrator\RunCommand::class,
                Migrator\SetupCommand::class,
                Migrator\StatusCommand::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Migrator\RollBackCommand::class => \MySchema\Helper\ConsoleCommandFactory::class,
                Migrator\RunCommand::class => \MySchema\Helper\ConsoleCommandFactory::class,
                Migrator\SetupCommand::class => \MySchema\Helper\ConsoleCommandFactory::class,
                Migrator\StatusCommand::class => \MySchema\Helper\ConsoleCommandFactory::class,
            ],
        ];
    }

    private function getMigrations(): array
    {
        return [
            'main' => [
                'initial' => [
                    'up' => '/resources/migrations/initial/up.sql',
                    'down' => '/resources/migrations/initial/down.sql',
                ],
            ],
        ];
    }
}
