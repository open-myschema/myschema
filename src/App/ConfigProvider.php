<?php

declare(strict_types=1);

namespace MySchema\App;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => $this->getCommands(),
            'dependencies' => $this->getDependencies(),
            'resources' => $this->getResources(),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getCommands(): array
    {
        return [
            'main:render-home-page' => Command\RenderHomePageCommand::class,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                AppManager::class => AppManagerFactory::class,
            ],
        ];
    }

    private function getResources(): array
    {
        return [
            'blocks' => [
                'main::dashboard-apps' => [
                    'file' => '/resources/blocks/dashboard/apps.html',
                    'innerHTML' => false,
                    'repeating' => true,
                ],
            ],
            'templates' => [
                'main::home-dashboard' => [
                    'file' => '/resources/templates/home/dashboard.twig',
                ],
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            '/' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::home',
                'options' => [
                    'template' => 'main::home-dashboard',
                    'command' => 'main:render-home-page',
                ],
            ],
            '/settings' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::settings-page',
                'options' => [
                    'template' => 'main::settings-dashboard',
                    'command' => 'main:render-settings-page',
                ],
            ],
        ];
    }
}
