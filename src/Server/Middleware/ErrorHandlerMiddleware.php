<?php

declare(strict_types=1);

namespace MySchema\Server\Middleware;

use MySchema\Server\ErrorResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(private ErrorResponseGenerator $errorResponseGenerator)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (\Throwable $e) {
            $response = $this->errorResponseGenerator->generateResponse($e);
        }

        return $response;
    }
}
