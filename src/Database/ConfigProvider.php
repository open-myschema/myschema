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
            'resources' => $this->getResources(),
        ];
    }

    private function getCommands(): array
    {
        return [
            'migrations:rollback' => Command\RollBackCommand::class,
            'migrations:run' => Command\RunCommand::class,
            'migrations:setup' => Command\SetupCommand::class,
            'migrations:status' => Command\StatusCommand::class,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Command\RollBackCommand::class => \MySchema\Command\CommandFactory::class,
                Command\RunCommand::class => \MySchema\Command\CommandFactory::class,
                Command\SetupCommand::class => \MySchema\Command\CommandFactory::class,
                Command\StatusCommand::class => \MySchema\Command\CommandFactory::class,
            ],
        ];
    }

    private function getMigrations(): array
    {
        return [
            'main::setup-migrations' => [
                'description' => 'Set up the migration table',
                'up' => '/resources/migrations/initial/up.sql',
                'down' => '/resources/migrations/initial/down.sql',
            ],
        ];
    }

    private function getResources(): array
    {
        return [
            'migrations' => $this->getMigrations(),
        ];
    }
}
