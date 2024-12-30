<?php

declare(strict_types=1);

namespace MySchema\Server\Middleware;

use MySchema\Server\ErrorResponseGenerator;
use Psr\Container\ContainerInterface;

final class ErrorHandlerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ErrorHandlerMiddleware
    {
        $errorResponseGenerator = $container->get(ErrorResponseGenerator::class);
        assert($errorResponseGenerator instanceof ErrorResponseGenerator);

        return new ErrorHandlerMiddleware($errorResponseGenerator);
    }
}
