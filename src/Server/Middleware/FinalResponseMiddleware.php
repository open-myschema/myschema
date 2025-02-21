<?php

declare(strict_types=1);

namespace MySchema\Server\Middleware;

use MySchema\Command\Output\Psr7ResponseOutput;
use MySchema\Platform\PlatformInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;

class FinalResponseMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $platform = $request->getAttribute(PlatformInterface::class);
        assert($platform instanceof PlatformInterface);

        return $platform->formatResponse(
            $request,
            new Psr7ResponseOutput()
        );
    }
}
