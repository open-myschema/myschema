<?php

declare(strict_types=1);

namespace MySchema\Action;

use Mezzio\Router\RouteResult;
use MySchema\Platform\PlatformInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ActionMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        assert($routeResult instanceof RouteResult);

        if ($routeResult->isFailure()) {
            return $handler->handle($request);
        }

        // get the action
        $route = $routeResult->getMatchedRoute();
        $actionClass = $route->getOptions()['action'];
        $action = new $actionClass;
        assert($action instanceof Action);

        // execute action
        $action->setParams([
            'requestMethod' => $request->getMethod(),
            'parsedBody' => $request->getParsedBody(),
            'queryParams' => $request->getQueryParams(),
            'serverParams' => $request->getServerParams(),
            'cookieParams' => $request->getCookieParams(),
            'headers' => $request->getHeaders(),
            'uploadedFiles' => $request->getUploadedFiles(),
            'uri' => (string) $request->getUri(),
        ]);
        $result = $action($this->container);

        // return the result
        $platform = $request->getAttribute(PlatformInterface::class);
        assert($platform instanceof PlatformInterface);
        
        return $platform->formatResponse($request, $result);
    }
}
