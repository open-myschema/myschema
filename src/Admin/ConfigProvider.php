<?php

declare(strict_types=1);

namespace MySchema\Admin;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'resources' => $this->getResources(),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getDependencies(): array
    {
        return [];
    }

    private function getResources(): array
    {
        return [
            'blocks' => [
                'main::top-navbar' => [
                    'file' => '/resources/blocks/navigation/navbar.html',
                ],
            ],
            'templates' => [
                'main::admin-dashboard' => [
                    'file' => '/resources/templates/dashboard.html',
                ],
                'main::content-dashboard' => [
                    'file' => '/resources/templates/admin/content-dashboard.html',
                ],
                'main::error-404' => [
                    'file' => '/resources/templates/error/404.html',
                ],
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            '/admin' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::admin-dashboard',
                'options' => [
                    'action' => Action\RenderAdminDashboard::class,
                    'template' => 'main::admin-dashboard'
                ],
            ],
            '/admin/content' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::content-dashboard',
                'options' => [
                    'action' => Action\RenderContentDashboard::class,
                    'template' => 'main::content-dashboard',
                ],
            ],
        ];
    }
}
