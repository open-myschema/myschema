<?php

declare(strict_types=1);

namespace MySchema\Security;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'resources' => [
                'migrations' => $this->getMigrations(),
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                \Laminas\InputFilter\InputFilterPluginManager::class => InputFilter\InputFilterPluginManagerFactory::class,
                \Laminas\Validator\ValidatorPluginManager::class => Validator\ValidatorPluginManagerFactory::class,
            ],
        ];
    }

    private function getMigrations(): array
    {
        return [
            'main::create-account-tables' => [
                'description' => 'Create account and account_meta tables',
                'postgres' => [
                    'up' => '/resources/migrations/postgres/account/up.sql',
                    'down' => '/resources/migrations/postgres/account/down.sql',
                ],
            ],
        ];
    }
}
