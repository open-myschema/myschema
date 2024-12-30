<?php

declare(strict_types=1);

namespace MySchema\Database;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'database' => $this->getDatabaseConfig(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDatabaseConfig(): array
    {
        return [
            'main' => [
                'driver' => 'pdo_sqlite',
                'driverOptions' => [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ],
                'memory' => TRUE,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [];
    }
}
