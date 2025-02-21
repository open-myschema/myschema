<?php

declare(strict_types=1);

namespace MySchema\Server\Middleware;

use Laminas\Diactoros\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Platform\PlatformInterface;

class FinalResponseMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = (new ResponseFactory())->createResponse(404);
        $output = $request->getAttribute(OutputInterface::class);
        if ($output instanceof Psr7ResponseOutputInterface) {
            if ($output->getDataType() === ResponseInterface::class) {
                return $output->getData();
            }

            $platform = $request->getAttribute(PlatformInterface::class);
            if ($platform instanceof PlatformInterface) {
                $response = $platform->formatResponse($request, $output);
            }
        }

        return $response;
    }
}
