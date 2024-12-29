<?php

declare(strict_types=1);

namespace MySchema\Server\Runtime\Provider;

use MySchema\Server\Runtime\RuntimeInterface;

class Apache2Handler implements RuntimeInterface
{
    public function run(): void
    {
        echo "Hello, world!<br>";
    }
}
