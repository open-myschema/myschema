<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Command;

use Psr\Container\ContainerInterface;

final class RenderTemplateCommandFactory
{
    public function __invoke(ContainerInterface $container): RenderTemplateCommand
    {
        return new RenderTemplateCommand($container);
    }
}
