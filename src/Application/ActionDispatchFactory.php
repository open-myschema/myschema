<?php

declare(strict_types=1);

namespace MySchema\Application;

use MySchema\Application\ActionDispatch;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ActionDispatchFactory
{
    public function __invoke(ContainerInterface $container): ActionDispatch
    {
        $listenerProvider = $container->get(ListenerProviderInterface::class);
        assert($listenerProvider instanceof ListenerProviderInterface);

        return new ActionDispatch($listenerProvider);
    }
}
