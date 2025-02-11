<?php

declare(strict_types=1);

namespace MySchema\Command;

use Mezzio\Router\RouteResult;
use MySchema\Command\Input\Psr7RequestInput;
use MySchema\Command\Output\Psr7ResponseOutput;
use MySchema\Platform\PlatformInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;
use Throwable;

use function array_key_exists;
use function assert;
use function get_debug_type;
use function sprintf;

class CommandMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container, private array $commandsConfig)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        if (! $routeResult instanceof RouteResult || $routeResult->isFailure()) {
            return $handler->handle($request);
        }

        // get the command
        $route = $routeResult->getMatchedRoute();
        $commandName = $route->getOptions()['command'] ?? 'platform:render-web-template';
        if (! array_key_exists($commandName, $this->commandsConfig)) {
            // @todo dispatch error event
            return $handler->handle($request);
        }
        try {
            $command = new $this->commandsConfig[$commandName]($this->container, $commandName);
            if (! $command instanceof BaseCommand) {
                throw new InvalidArgumentException(sprintf(
                    "Invalid command class %s. Expected an instance of %s",
                    get_debug_type($command),
                    BaseCommand::class
                ));
            }
        } catch (Throwable) {
            // @todo dispatch error event
            return $handler->handle($request);
        }

        // execute action
        $input = new Psr7RequestInput($request, $command->getDefinition());
        $output = new Psr7ResponseOutput;
        $command->execute($input, $output);

        // return the result
        $platform = $request->getAttribute(PlatformInterface::class);
        assert($platform instanceof PlatformInterface);

        return $platform->formatResponse($request, $output);
    }
}
