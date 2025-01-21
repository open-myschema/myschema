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
use function method_exists;
use MySchema\Platform\Web\Action\RenderTemplateAction;

class ActionMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        if (! $routeResult instanceof RouteResult || $routeResult->isFailure()) {
            return $handler->handle($request);
        }

        // get the action
        $route = $routeResult->getMatchedRoute();
        $actionClass = $route->getOptions()['action'] ?? RenderTemplateAction::class;
        try {
            $action = new $actionClass;
        } catch (\Throwable) {
            return $handler->handle($request);
        }

        if (! $action instanceof Action) {
            return $handler->handle($request);
        }

        if (method_exists($action, 'setRequest')) {
            $action->setRequest($request);
        }

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
        if (! $platform instanceof PlatformInterface) {
            return $handler->handle($request);
        }

        return $platform->formatResponse($request, $result);
    }
}
