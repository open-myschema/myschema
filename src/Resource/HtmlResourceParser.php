<?php

declare(strict_types=1);

namespace MySchema\Resource;

class HtmlResourceParser implements ResourceParserInterface
{
    public function parseResource(string $contents): array|string
    {
        return $contents;
    }
}
