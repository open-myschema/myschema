<?php

declare(strict_types=1);

namespace MySchema\Platform;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'actions' => $this->getActionsConfig(),
            'commands' => $this->getCommands(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getActionsConfig(): array
    {
        return [
            Web\Listener\RenderTemplateListener::class => [
                Web\Event\HtmlRenderedEvent::class,
            ],
        ];
    }

    private function getCommands(): array
    {
        return [
            'platform:render-web-template' => Web\Command\RenderTemplateCommand::class,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                PlatformMiddleware::class => PlatformMiddlewareFactory::class,
                RestAPI\RestAPIPlatform::class => RestAPI\RestAPIPlatformFactory::class,
                Web\Command\RenderTemplateCommand::class => \MySchema\Command\CommandFactory::class,
                Web\Template\Engine\DomTemplate\DomTemplateRenderer::class => Web\Template\Engine\DomTemplate\DomTemplateRendererFactory::class,
                Web\Template\Engine\HtmlTemplate\HtmlTemplateRenderer::class => Web\Template\Engine\HtmlTemplate\HtmlTemplateRenderer::class,
                Web\Template\Engine\Twig\TwigTemplateRenderer::class => Web\Template\Engine\Twig\TwigTemplateRendererFactory::class,
                Web\Template\TemplateRendererInterface::class => Web\Template\Engine\HtmlTemplate\HtmlTemplateRendererFactory::class,
                Web\WebPlatform::class => Web\WebPlatformFactory::class,
            ],
        ];
    }
}
