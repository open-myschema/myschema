<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

use MySchema\Resource\ResourceManager;
use Psr\Container\ContainerInterface;

class DomTemplateRendererFactory
{
    public function __invoke(ContainerInterface $container): DomTemplateRenderer
    {
        $resourceManager = $container->get(ResourceManager::class);
        assert($resourceManager instanceof ResourceManager);

        return new DomTemplateRenderer($resourceManager);
    }
}
