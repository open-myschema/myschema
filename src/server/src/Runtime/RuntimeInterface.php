<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime;

interface RuntimeInterface
{
    public function run(): void;
}
