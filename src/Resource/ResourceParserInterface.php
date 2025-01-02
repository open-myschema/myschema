<?php

declare(strict_types=1);

namespace MySchema\Resource;

interface ResourceParserInterface
{
    public function parseResource(string $contents): array;
}
