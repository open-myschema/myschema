<?php

declare(strict_types=1);

namespace MySchema\Security;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'migrations' => $this->getMigrations(),
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
            'main' => [
                'accounts' => [
                    'description' => 'Create account and account_meta tables',
                    'up' => '/resources/migrations/account/up.sql',
                    'down' => '/resources/migrations/account/down.sql',
                ]
            ],
        ];
    }
}
