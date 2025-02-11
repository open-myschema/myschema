<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\HtmlTemplate;

use Laminas\Escaper\Escaper;
use MySchema\Helper\ServiceFactoryTrait;
use Psr\Container\ContainerInterface;

final class HtmlTemplateRendererFactory
{
    use ServiceFactoryTrait;

    public function __invoke(ContainerInterface $container): HtmlTemplateRenderer
    {
        $resourceManager = $this->getResourceManager($container);
        $escaper = new Escaper();
        return new HtmlTemplateRenderer($resourceManager, $escaper);
    }
}
