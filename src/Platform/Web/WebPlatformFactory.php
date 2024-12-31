<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Psr\Container\ContainerInterface;

final class WebPlatformFactory
{
    public function __invoke(ContainerInterface $container): WebPlatform
    {
        return new WebPlatform($container);
    }
}
