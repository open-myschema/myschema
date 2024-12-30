<?php

declare(strict_types=1);

namespace MySchema\Application;

use Mezzio\Router\RouteResult;
use MySchema\Page\RenderPageAction;
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
        ]);
        $action($this->container);

        return $handler->handle($request);
    }
}
