<?php

declare(strict_types=1);

namespace MySchema\Server;

use Psr\Container\ContainerInterface;

final class ErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container): ErrorResponseGenerator
    {
        $isDebugMode = $container->get('config')['debug'] ?? FALSE;
        return new ErrorResponseGenerator($isDebugMode);
    }
}
