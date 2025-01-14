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
            'routes' => $this->getRoutes(),
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
            'blocks' => [
                'content::error-404' => '/resources/blocks/404.json',
                'main::content-dashboard' => '/resources/blocks/dashboard.json',
                "main::top-navbar" => '/resources/blocks/navigation/navbar.json',
            ],
            'forms' => [],
            'queries' => [],
            'templates' => [
                'content::error-404' => '/resources/templates/error/404.json',
                'main::content-dashboard' => '/resources/templates/dashboard.json',
            ],
            'translations' => [],
        ];
    }

    private function getRoutes(): array
    {
        return [
            '/' => [
                'methods' => ['GET', 'POST'],
                'name' => 'content::dashboard',
                'options' => [
                    'action' => \RiverBedDynamics\Action\SimulateStorm::class,
                    'template' => 'main::content-dashboard'
                ],
            ],
        ];
    }
}
