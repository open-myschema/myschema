<?php

declare(strict_types=1);

namespace MySchema\Helper;

use MySchema\Resource\ResourceManager;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

trait ServiceFactoryTrait
{
    public function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(EventDispatcherInterface::class);
    }

    public function getResourceManager(ContainerInterface $container): ResourceManager
    {
        return $container->get(ResourceManager::class);
    }
}
