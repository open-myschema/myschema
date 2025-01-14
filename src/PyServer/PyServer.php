<?php

declare(strict_types=1);

namespace MySchema\PyServer;

use Laminas\Diactoros\RequestFactory;
use Psr\Http\Client\ClientInterface;
use function json_encode;

class PyServer
{
    public function __construct(private ClientInterface $httpClient, private string $pyserverHost)
    {
    }

    public function runScript(string $app, string $module, string $script, array $args = []): string
    {
        // create a PSR-7 request
        $request = (new RequestFactory)->createRequest(
            'POST',
            $this->pyserverHost
        );

        // prepare the request body
        $payload = json_encode([
            'app' => $app,
            'module' => $module,
            'script' => $script,
            'args' => json_encode($args),
        ]);

        // append the body
        $body = $request->getBody();
        $body->write($payload);
        $body->rewind();
        $request = $request->withBody($body);

        // return a response
        $response = $this->httpClient->sendRequest($request);
        return $response->getBody()->getContents();
    }
}
