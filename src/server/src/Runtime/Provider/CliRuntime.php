<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use MySchema\Server\Runtime\RuntimeInterface;
use Symfony\Component\Console\Application;

class CliRuntime implements RuntimeInterface
{
    public function __construct(private Application $application)
    {
    }

    public function run(): void
    {
        $this->application->run();
    }
}
