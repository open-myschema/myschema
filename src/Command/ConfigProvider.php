<?php

declare(strict_types=1);

namespace MySchema\Command;

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
                CommandMiddleware::class => CommandMiddlewareFactory::class,
            ],
        ];
    }
}
