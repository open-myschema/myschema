<?php

declare(strict_types=1);

namespace MySchema\Server;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'console' => $this->getConsoleCommands(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getConsoleCommands(): array
    {
        return [
            'commands' => [
                Command\HelloWorldCommand::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Command\HelloWorldCommand::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                Runtime\Provider\Apache2Handler::class => Runtime\Provider\Apache2HandlerFactory::class,
                Runtime\Provider\CliRuntime::class => Runtime\Provider\CliRuntimeFactory::class,
            ],
        ];
    }
}
