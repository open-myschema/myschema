<?php

declare(strict_types=1);

namespace MySchema\Application;

abstract class Action
{
    abstract public function assertAuthorization(): bool;
    abstract public function isValid(): bool;
}
