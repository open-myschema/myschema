<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use MySchema\Action\ActionResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface PlatformInterface
{
    public function formatResponse(ServerRequestInterface $request, ActionResult $result): ResponseInterface;
}
