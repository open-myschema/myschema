<?php

declare(strict_types=1);

namespace MySchema\PyServer;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'pyserver_config' => $this->getPyServerConfig(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                PyServer::class => PyServerFactory::class,
            ],
        ];
    }

    private function getPyServerConfig(): array
    {
        return [
            'host' => 'http://localhost:5000',
        ];
    }
}
