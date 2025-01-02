<?php

declare(strict_types=1);

namespace MySchema\Action;

interface ActionHandlerInterface
{
    public function getListeners(): array;
}
