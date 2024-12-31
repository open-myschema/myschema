<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use MySchema\Application\ActionResult;
use Psr\Http\Message\ResponseInterface;

class SimpleJsonRenderer implements AcionResultRendererInterface
{
    public function render(ActionResult $result): string
    {
        $data = match ($result->getDataType()) {
            "array" => $result->getData(),
            "int", "string", "null", "bool" => ['value' => $result->getData()],
            ResponseInterface::class => [$result->getData()->getBody()->getContents()],
            default => [],
        };

        return \json_encode($data);
    }
}
