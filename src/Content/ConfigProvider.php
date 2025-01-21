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
                'main::content-dashboard' => '/resources/blocks/dashboard.html',
                "main::top-navbar" => '/resources/blocks/navigation/navbar.html',
            ],
            'forms' => [],
            'queries' => [],
            'templates' => [
                'main::error-404' => '/resources/templates/error/404.html',
                'main::content-dashboard' => '/resources/templates/dashboard.html',
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            '/' => [
                'methods' => ['GET', 'POST'],
                'name' => 'content::dashboard',
                'options' => [
                    'action' => Action\DisplayDashboardAction::class,
                    'template' => 'main::content-dashboard'
                ],
            ],
        ];
    }
}
