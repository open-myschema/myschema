<?php

declare(strict_types=1);

namespace MySchema\Admin;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
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
            '/admin/page/new' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::admin-create-page',
                'options' => [
                    'action' => \MySchema\Page\Action\CreatePageAction::class,
                ],
            ],
        ];
    }
}
