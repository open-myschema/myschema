<?php

declare(strict_types= 1);

namespace MySchema\Platform\RestAPI;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestAPIPlatform implements PlatformInterface
{
    public function formatResponse(ServerRequestInterface $request, OutputInterface $output): ResponseInterface
    {
        // @todo switch to a more advanced renderer
        $renderer = new SimpleJsonRenderer();
        $data = $renderer->render($output);

        $jsonHeaders = (new JsonResponse([]))->getHeaders();
        $mergedHeaders = \array_merge($output->getHeaders(), $jsonHeaders);

        // @todo this wont work?!
        return new Response($data, $output->getCode(), $mergedHeaders);
    }
}
