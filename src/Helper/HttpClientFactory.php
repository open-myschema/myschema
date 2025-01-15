<?php

declare(strict_types=1);

namespace MySchema\Helper;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

final class HttpClientFactory
{
    public function __invoke(ContainerInterface $container): ClientInterface
    {
        $guzzleConfig = $container->get('config')['guzzle_client'] ?? [];
        return new Client($guzzleConfig);
    }
}
