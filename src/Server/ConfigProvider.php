<?php

declare(strict_types=1);

namespace MySchema\Server;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'actions' => $this->getActionsConfig(),
            'console' => $this->getConsoleCommands(),
            'dependencies' => $this->getDependencies(),
            'middleware_pipeline' => $this->getMiddlewarePipeline(),
        ];
    }

    private function getActionsConfig(): array
    {
        return [
            Action\ServerActionsListener::class => [
                Action\HttpRequestAction::class,
            ],
        ];
    }

    private function getConsoleCommands(): array
    {
        return [
            'commands' => [
                Console\HelloWorldCommand::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Console\HelloWorldCommand::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                ErrorResponseGenerator::class => ErrorResponseGeneratorFactory::class,
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
            Middleware\FinalResponseMiddleware::class,
        ];
    }
}
