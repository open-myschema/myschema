<?php

declare(strict_types=1);

namespace MySchema\Resource;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                ResourceManager::class => ResourceManagerFactory::class,
            ],
        ];
    }
}
