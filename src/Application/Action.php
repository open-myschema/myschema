<?php

declare(strict_types=1);

namespace MySchema\Application;

use Psr\Container\ContainerInterface;

abstract class Action
{
    public function __invoke(ContainerInterface $container): void
    {
    }

    abstract public function assertAuthorization(): bool;
    abstract public function isValid(): bool;
}
