<?php

declare(strict_types=1);

namespace MySchema\Database;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => $this->getCommands(),
            'dependencies' => $this->getDependencies(),
            'migrations' => $this->getMigrations(),
        ];
    }

    private function getCommands(): array
    {
        return [
            'migrations:rollback' => Migrator\RollBackCommand::class,
            'migrations:run' => Migrator\RunCommand::class,
            'migrations:setup' => Migrator\SetupCommand::class,
            'migrations:status' => Migrator\StatusCommand::class,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Migrator\RollBackCommand::class => \MySchema\Command\CommandFactory::class,
                Migrator\RunCommand::class => \MySchema\Command\CommandFactory::class,
                Migrator\SetupCommand::class => \MySchema\Command\CommandFactory::class,
                Migrator\StatusCommand::class => \MySchema\Command\CommandFactory::class,
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
