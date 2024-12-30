<?php

declare(strict_types=1);

namespace MySchema\Application;

final class ConfigProvider
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
                \Psr\EventDispatcher\EventDispatcherInterface::class => ActionDispatchFactory::class,
            ],
        ];
    }
}
