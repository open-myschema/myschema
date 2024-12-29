<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use Psr\Container\ContainerInterface;

final class Apache2HandlerFactory
{
    public function __invoke(ContainerInterface $container): Apache2Handler
    {
        return new Apache2Handler;
    }
}
