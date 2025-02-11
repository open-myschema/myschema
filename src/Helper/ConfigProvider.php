<?php

declare(strict_types=1);

namespace MySchema\Helper;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'guzzle_client' => $this->getGuzzleClientConfig(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                \Psr\Http\Client\ClientInterface::class => HttpClientFactory::class,
            ],
        ];
    }

    private function getGuzzleClientConfig(): array
    {
        return [];
    }
}
