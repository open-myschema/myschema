<?php

declare(strict_types=1);

namespace MySchema\Server;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Escaper\Escaper;
use Psr\Http\Message\ResponseInterface;

class ErrorResponseGenerator
{
    public function __construct(private bool $isDebugMode)
    {
    }

    public function generateResponse(\Throwable $e): ResponseInterface
    {
        $escaper = new Escaper();
        $message = $this->isDebugMode
            ? $escaper->escapeHtml($e->getMessage() . " " . $e->getTraceAsString())
            : "An error occurred";

        // @todo determine response type
        // @todo template error response in debug mode
        return new HtmlResponse($message, StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }
}
