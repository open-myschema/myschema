<?php

declare(strict_types=1);

namespace MySchema\Platform;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                Web\TemplateRendererResolverInterface::class => Web\DomTemplate\DomTemplateRendererResolver::class,
            ],
            'factories' => [
                PlatformMiddleware::class => PlatformMiddlewareFactory::class,
                RestAPI\RestAPIPlatform::class => RestAPI\RestAPIPlatformFactory::class,
                Web\DomTemplate\DomTemplateRendererResolver::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
                Web\WebPlatform::class => Web\WebPlatformFactory::class,
            ],
        ];
    }
}
