<?php

declare(strict_types=1);

namespace MySchema\Action;

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
                ActionMiddleware::class => ActionMiddlewareFactory::class,
                \Psr\EventDispatcher\EventDispatcherInterface::class => ActionDispatchFactory::class,
            ],
        ];
    }
}
