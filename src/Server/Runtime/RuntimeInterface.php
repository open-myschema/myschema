<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime;

/**
 * Abstract away PHP runtimes
 */
interface RuntimeInterface
{
    public function run(): void;
}
