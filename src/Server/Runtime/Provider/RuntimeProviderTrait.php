<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use Mezzio\Router\RouteCollectorInterface;
use MySchema\Command\CommandMiddleware;
use MySchema\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

use function strtolower;

trait RuntimeProviderTrait
{
    private function setupRouting(ContainerInterface $container): void
    {
        // @todo validate routes
        $routes = $container->get('config')['routes'] ?? [];
        foreach ($container->get('apps') ?? [] as $app => $appConfig) {
            if (! isset($appConfig['routes'])) {
                continue;
            }

            $prefix = $appConfig['info']['tag'] ?? strtolower($app);
            foreach ($appConfig['routes'] ?? [] as $routePrefix => $routeConfig) {
                $routes["/$prefix$routePrefix"] = $routeConfig;
            }
        }

        $routeCollector = $container->get(RouteCollectorInterface::class);
        foreach ($routes as $pattern => $routeConfig) {
            // prep middleware
            $middleware = $routeConfig['middleware'] ?? [];
            if (empty($middleware)) {
                $middleware = CommandMiddleware::class;
            }

            // collect route
            $route = $routeCollector->route(
                path: $pattern,
                middleware: new LazyLoadingMiddleware($container, $middleware),
                methods: $routeConfig['methods'] ?? ['GET'],
                name: $routeConfig['name'],
            );

            // set options
            $route->setOptions($routeConfig['options'] ?? []);
        }
    }
}
