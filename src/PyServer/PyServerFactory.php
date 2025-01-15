<?php

declare(strict_types=1);

namespace MySchema\PyServer;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use function get_debug_type;
use function sprintf;

final class PyServerFactory
{
    private const string DEFAULT_HOST = 'http://localhost:5000';

    public function __invoke(ContainerInterface $container): PyServer
    {
        $httpClient = $container->get(ClientInterface::class);
        if (! $httpClient instanceof ClientInterface) {
            throw new \InvalidArgumentException(sprintf(
                "Expected an instance of %s, found %s instead",
                ClientInterface::class,
                get_debug_type($httpClient)
            ));
        }

        $appsConfig = $container->get('apps') ?? [];
        $pyServerConfig = $container->get('config')['pyserver_config'] ?? [];
        $host = $pyServerConfig['host'] ?? self::DEFAULT_HOST;

        return new PyServer($httpClient, $host, $appsConfig);
    }
}
