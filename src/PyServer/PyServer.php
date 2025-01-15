<?php

declare(strict_types=1);

namespace MySchema\PyServer;

use Laminas\Diactoros\RequestFactory;
use Psr\Http\Client\ClientInterface;
use function json_encode;
use function json_decode;
use function sprintf;

class PyServer
{
    public function __construct(
        private ClientInterface $httpClient,
        private string $pyserverHost,
        private array $appsConfig
    ) {
    }

    public function runScript(string $app, string $module, string $function, array $args = []): array
    {
        if (! isset($this->appsConfig[$app])) {
            throw new \InvalidArgumentException(sprintf(
                "App %s not found in config",
                $app
            ));
        }

        // get app's python config
        $config = $this->appsConfig[$app]['python'] ?? [];

        // create a PSR-7 request
        $request = (new RequestFactory)->createRequest(
            'POST',
            $this->pyserverHost
        );

        // prepare the request body
        $payload = json_encode([
            'module' => "apps.$app.$module",
            'function' => $function,
            'args' => json_encode($args),
            'paths' => json_encode($config['additional_paths'] ?? []),
        ]);

        // append the body
        $body = $request->getBody();
        $body->write($payload);
        $body->rewind();
        $request = $request->withBody($body);

        // set header
        $request = $request->withHeader('content-type', 'application/json');

        // return a response
        $response = $this->httpClient->sendRequest($request);
        return json_decode($response->getBody()->getContents(), true);
    }
}
