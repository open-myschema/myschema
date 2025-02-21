<?php

declare(strict_types=1);

namespace MySchema\Platform;

use MySchema\Platform\RestAPI\RestAPIPlatform;
use MySchema\Platform\Web\WebPlatform;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;

use function get_debug_type;
use function mb_strpos;
use function sprintf;

class PlatformMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $platform = false !== mb_strpos($request->getUri()->getPath(), '/api/')
            ? $this->container->get(RestAPIPlatform::class)
            : $this->container->get(WebPlatform::class);

        if (! $platform instanceof PlatformInterface) {
            throw new InvalidArgumentException(sprintf(
                "Expected an instance of %s, found %s instead",
                PlatformInterface::class,
                get_debug_type($platform)
            ));
        }

        return $handler->handle($request->withAttribute(PlatformInterface::class, $platform));
    }
}
