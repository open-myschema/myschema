<?php

declare(strict_types=1);

namespace MySchema\Command\Input;

use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;

use function array_key_exists;
use function assert;

class Psr7RequestInput extends ArrayInput
{
    public function __construct(ServerRequestInterface $request, InputDefinition $commandInputDefinition)
    {
        $parameters = $this->resolveCommandArguments($request, $commandInputDefinition);
        parent::__construct($parameters, $commandInputDefinition);
    }

    private function resolveCommandArguments(ServerRequestInterface $request, InputDefinition $inputDefinition): array
    {
        $resolved = [];
        $routeResult = $request->getAttribute(RouteResult::class);
        assert($routeResult instanceof RouteResult);

        $routeOptions = $routeResult->getMatchedRoute()->getOptions() ?? [];
        foreach ($inputDefinition->getOptions() as $option) {
            $name = $option->getName();
            if (array_key_exists($name, $routeOptions)) {
                $resolved["--$name"] = $routeOptions[$name];
            }

            if (array_key_exists($name, $request->getParsedBody())) {
                $resolved["--$name"] = $routeOptions[$name];
            }

            if (array_key_exists($name, $request->getQueryParams())) {
                $resolved["--$name"] = $routeOptions[$name];
            }
        }

        foreach ($inputDefinition->getArguments() as $argument) {
            $name = $argument->getName();
            if (array_key_exists($name, $routeOptions)) {
                $resolved[$name] = $routeOptions[$name];
            }

            if (array_key_exists($name, $request->getParsedBody())) {
                $resolved[$name] = $routeOptions[$name];
            }

            if (array_key_exists($name, $request->getQueryParams())) {
                $resolved[$name] = $routeOptions[$name];
            }
        }

        return $resolved;
    }
}
