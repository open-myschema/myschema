<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

interface DomTemplateParserInterface
{
    public function parse(string $contents): array;
}
