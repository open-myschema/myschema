<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

use function json_decode;

class DomJsonTemplateParser implements DomTemplateParserInterface
{
    public function parse(string $contents): array
    {
        return \json_decode($contents, true);
    }
}
