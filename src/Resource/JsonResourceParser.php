<?php

declare(strict_types=1);

namespace MySchema\Resource;

use function json_decode;

class JsonResourceParser implements ResourceParserInterface
{
    public function parseResource(string $contents): array
    {
        return json_decode($contents, TRUE);
    }
}
