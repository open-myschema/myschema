<?php

declare(strict_types=1);

namespace MySchema\Security\InputFilter;

use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\ServiceManager\Config;
use Psr\Container\ContainerInterface;

final class InputFilterPluginManagerFactory
{
    public function __invoke(ContainerInterface $container): InputFilterPluginManager
    {
        $pluginManager = new InputFilterPluginManager($container, $options ?? []);

        $config = $container->get('config')['input_filters'] ?? [];
        $apps = $container->get('apps');
        foreach ($apps as $app) {
            if (! isset($app['input_filters'])) {
                continue;
            }

            $config = \array_merge($config, $app['input_filters']);
        }

        // If we do not have input_filters configuration, nothing more to do
        if (empty($config)) {
            return $pluginManager;
        }

        // Wire service configuration for input_filters
        (new Config($config))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}
