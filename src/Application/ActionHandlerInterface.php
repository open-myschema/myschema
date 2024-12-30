<?php

declare(strict_types=1);

namespace MySchema\Application;

interface ActionHandlerInterface
{
    public function getListeners(): array;
}
