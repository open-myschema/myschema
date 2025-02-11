<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use MySchema\Command\CommandOutputRendererInterface;
use MySchema\Command\Psr7ResponseOutputInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function json_encode;

class SimpleJsonRenderer implements CommandOutputRendererInterface
{
    public function render(OutputInterface $output): string
    {
        assert($output instanceof Psr7ResponseOutputInterface);

        if ($output->hasError()) {
            //
        }
        $data = match ($output->getDataType()) {
            "array" => $output->getData(),
            "int", "string", "null", "bool" => ['value' => $output->getData()],
            ResponseInterface::class => [$output->getData()->getBody()->getContents()],
            default => [],
        };

        return json_encode($data);
    }
}
