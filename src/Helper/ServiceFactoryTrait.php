<?php

declare(strict_types=1);

namespace MySchema\Helper;

use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\Validator\ValidatorPluginManager;
use MySchema\PyServer\PyServer;
use MySchema\Resource\ResourceManager;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

trait ServiceFactoryTrait
{
    public function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(EventDispatcherInterface::class);
    }

    public function getInputFilterManager(ContainerInterface $container): InputFilterPluginManager
    {
        return $container->get(InputFilterPluginManager::class);
    }

    public function getPyServer(ContainerInterface $container): PyServer
    {
        return $container->get(PyServer::class);
    }

    public function getResourceManager(ContainerInterface $container): ResourceManager
    {
        return $container->get(ResourceManager::class);
    }
    public function getValidatorPluginManager(ContainerInterface $container): ValidatorPluginManager
    {
        return $container->get(ValidatorPluginManager::class);
    }

}
