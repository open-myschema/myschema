<?php

declare(strict_types= 1);

namespace MySchema\Platform\RestAPI;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use MySchema\Action\ActionResult;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RestAPIPlatform implements PlatformInterface
{
    public function formatResponse(ServerRequestInterface $request, ActionResult $result): ResponseInterface
    {
        // @todo switch to a more advanced renderer
        $renderer = new SimpleJsonRenderer();
        $data = $renderer->render($result);

        $jsonHeaders = (new JsonResponse([]))->getHeaders();
        $mergedHeaders = \array_merge($result->getHeaders(), $jsonHeaders);

        // @todo this wont work?!
        return new Response($data, $result->getCode(), $mergedHeaders);
    }
}
