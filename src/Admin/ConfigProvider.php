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

    private function getRoutes(): array
    {
        return [
            '/' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::dashboard',
                'options' => [
                    'template' => 'main::dashboard'
                ],
            ],
        ];
    }

    private function getResources(): array
    {
        return [
            'blocks' => [
                'admin::error-404' => '/resources/blocks/404.json',
                'admin::create-page-form' => '/resources/blocks/create-page-form.json',
            ],
            'forms' => [
                'admin::create-page' => '/resources/forms/create-page.json',
            ],
            'queries' => [
                'admin::create-page' => '/resources/queries/create-page.sql',
            ],
            'templates' => [
                'admin::create-page' => '/resources/templates/page/create.json',
                'admin::error-404' => '/resources/templates/error/404.json',
            ],
            'translations' => [],
        ];
    }
}
