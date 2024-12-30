<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Apache2HandlerFactory
{
    public function __invoke(ContainerInterface $container): Apache2Handler
    {
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
        return new Apache2Handler($eventDispatcher);
    }
}
