<?php

declare(strict_types=1);

namespace MySchema\Security\Validator;

use Laminas\ServiceManager\Config;
use Laminas\Validator\ValidatorPluginManager;
use Psr\Container\ContainerInterface;

final class ValidatorPluginManagerFactory
{
    public function __invoke(ContainerInterface $container): ValidatorPluginManager
    {
        $pluginManager = new ValidatorPluginManager($container, $options ?? []);

        $config = $container->get('config')['validators'] ?? [];
        $apps = $container->get('apps');
        foreach ($apps as $app) {
            if (! isset($app['validators'])) {
                continue;
            }

            $config = \array_merge($config, $app['validators']);
        }

        // If we do not have validators configuration, nothing more to do
        if (empty($config)) {
            return $pluginManager;
        }

        // Wire service configuration for validators
        (new Config($config))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}
