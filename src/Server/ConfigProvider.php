<?php

declare(strict_types=1);

namespace MySchema\Server;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'actions' => $this->getActionsConfig(),
            'dependencies' => $this->getDependencies(),
            'middleware_pipeline' => $this->getMiddlewarePipeline(),
        ];
    }

    private function getActionsConfig(): array
    {
        return [
            Listener\ServerActionsListener::class => [
                Event\HttpRequestEvent::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                ErrorResponseGenerator::class => ErrorResponseGeneratorFactory::class,
                \Mezzio\Router\RouterInterface::class => RouterFactory::class,
                Middleware\ErrorHandlerMiddleware::class => Middleware\ErrorHandlerMiddlewareFactory::class,
                Middleware\FinalResponseMiddleware::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                Runtime\Provider\Apache2Handler::class => Runtime\Provider\Apache2HandlerFactory::class,
                Runtime\Provider\CliRuntime::class => Runtime\Provider\CliRuntimeFactory::class,
            ],
        ];
    }

    private function getMiddlewarePipeline(): array
    {
        return [
            Middleware\ErrorHandlerMiddleware::class,
            \MySchema\Platform\PlatformMiddleware::class,
            \Mezzio\Router\Middleware\RouteMiddleware::class,
            \Mezzio\Router\Middleware\DispatchMiddleware::class,
            Middleware\FinalResponseMiddleware::class,
        ];
    }
}
