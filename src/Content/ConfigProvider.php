<?php

declare(strict_types=1);

namespace MySchema\Content;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'migrations' => $this->getMigrations(),
            'resources' => $this->getResources(),
        ];
    }

    private function getDependencies(): array
    {
        return [];
    }

    private function getMigrations(): array
    {
        return [
            'main' => [
                'content' => [
                    'description' => 'Create content tables',
                    'up' => '/resources/migrations/content/up.sql',
                    'down' => '/resources/migrations/content/down.sql',
                ],
            ],
        ];
    }

    private function getResources(): array
    {
        return [
            'queries' => [
                'main::content-types' => [
                    'postgres' => '/resources/queries/postgres/content/content_types.sql',
                ],
            ],
        ];
    }
}
