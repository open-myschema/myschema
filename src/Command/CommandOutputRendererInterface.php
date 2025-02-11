<?php

declare(strict_types=1);

namespace MySchema\Command;

use Symfony\Component\Console\Output\OutputInterface;

interface CommandOutputRendererInterface
{
    public function render(OutputInterface $output): string;
}
