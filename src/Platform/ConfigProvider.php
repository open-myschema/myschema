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
            'factories' => [
                PlatformMiddleware::class => PlatformMiddlewareFactory::class,
                RestAPI\RestAPIPlatform::class => RestAPI\RestAPIPlatformFactory::class,
                Web\DomTemplate\DomTemplateRenderer::class => Web\DomTemplate\DomTemplateRendererFactory::class,
                Web\WebPlatform::class => Web\WebPlatformFactory::class,
            ],
        ];
    }
}
