<?php

declare(strict_types=1);

namespace MySchema\Application;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

trait ServiceFactoryTrait
{
    public function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(EventDispatcherInterface::class);
    }
}
