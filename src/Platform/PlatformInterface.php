<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface PlatformInterface
{
    public function formatResponse(ServerRequestInterface $request, OutputInterface $output): ResponseInterface;
}
