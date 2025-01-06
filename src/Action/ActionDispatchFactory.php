<?php

declare(strict_types=1);

namespace MySchema\Action;

use MySchema\Action\ActionDispatch;
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
