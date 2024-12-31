<?php

declare(strict_types= 1);

namespace MySchema\Platform\RestAPI;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use MySchema\Application\ActionResult;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RestAPIPlatform implements PlatformInterface
{
    public function formatResponse(
        ServerRequestInterface $request,
        ActionResult $result,
        int $statusCode = StatusCodeInterface::STATUS_OK,
        array $headers = [],
    ): ResponseInterface {
        // @todo switch to a more advanced renderer
        $renderer = new SimpleJsonRenderer();
        $data = $renderer->render($result);

        $jsonHeaders = (new JsonResponse([]))->getHeaders();
        $mergedHeaders = \array_merge($headers, $jsonHeaders);

        // @todo this wont work?!
        return new Response($data, $statusCode, $mergedHeaders);
    }
}
