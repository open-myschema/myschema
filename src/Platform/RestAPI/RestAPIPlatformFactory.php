<?php

declare(strict_types= 1);

namespace MySchema\Platform\RestAPI;

use Psr\Container\ContainerInterface;

final class RestAPIPlatformFactory
{
    public function __invoke(ContainerInterface $container): RestAPIPlatform
    {
        return new RestAPIPlatform();
    }
}
