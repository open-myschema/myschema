<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use Fig\Http\Message\StatusCodeInterface;
use MySchema\Application\ActionResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface PlatformInterface
{
    public function formatResponse(
        ServerRequestInterface $request,
        ActionResult $result,
        int $statusCode = StatusCodeInterface::STATUS_OK,
        array $headers = []
    ): ResponseInterface;
}
