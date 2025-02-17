<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Template\Engine\Twig;

use Psr\Container\ContainerInterface;
use MySchema\Helper\ServiceFactoryTrait;

final class TwigTemplateRendererFactory
{
    use ServiceFactoryTrait;

    public function __invoke(ContainerInterface $container): TwigTemplateRenderer
    {
        $resourceManager = $this->getResourceManager($container);
        $twigEnvironmentOptions = [
            'debug' => $container->get('config')['debug'] ?? false,
        ];

        return new TwigTemplateRenderer($resourceManager, $twigEnvironmentOptions);
    }
}
