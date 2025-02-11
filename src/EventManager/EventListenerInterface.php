<?php

declare(strict_types=1);

namespace MySchema\EventManager;

interface EventListenerInterface
{
    public function getListeners(): array;
}
