<?php

declare(strict_types=1);

namespace MySchema\PyServer;

use Psr\Container\ContainerInterface;

final class PyServerFactory
{
    public function __invoke(ContainerInterface $container): PyServer
    {
        $httpClient = new \GuzzleHttp\Client();
        return new PyServer(
            $httpClient,
            'http://localhost:8000'
        );
    }
}
