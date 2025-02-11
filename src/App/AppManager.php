<?php

declare(strict_types=1);

namespace MySchema\App;

class AppManager
{
    public function __construct(private array $config)
    {
    }

    public function getAppsInfo(): array
    {
        $info = [];
        foreach ($this->config as $key => $appConfig) {
            if (! isset($appConfig['info'])) {
                continue;
            }

            $url = $appConfig['info']['route_prefix'] ?? "/$key";
            $info[$key] = [
                'name' => $appConfig['info']['name'] ?? $key,
                'href' => "/a$url",
            ];
        }

        return $info;
    }
}
