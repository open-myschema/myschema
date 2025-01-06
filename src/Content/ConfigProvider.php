<?php

declare(strict_types=1);

namespace MySchema\Content;

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
                'content::error-404' => '/resources/blocks/404.json',
            ],
            'forms' => [],
            'queries' => [],
            'templates' => [
                'content::error-404' => '/resources/templates/error/404.json',
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
                    'template' => 'content::dashboard'
                ],
            ],
        ];
    }
}