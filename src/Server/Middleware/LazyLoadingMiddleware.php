<?php

declare(strict_types= 1);

namespace MySchema\Server\Middleware;

use Laminas\Stratigility\Middleware\CallableMiddlewareDecorator;
use Laminas\Stratigility\Middleware\RequestHandlerMiddleware;
use Laminas\Stratigility\MiddlewarePipe;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LazyLoadingMiddleware implements MiddlewareInterface
{
    private $middleware;

    public function __construct(
        private ContainerInterface $container,
        string|array|callable|RequestHandlerInterface|MiddlewareInterface $middleware
    ) {
        $this->middleware = $middleware;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->middleware instanceof MiddlewareInterface) {
            return $this->middleware->process($request, $handler);
        }

        // convert RequestHandlerInterface objects to MiddlewareInterface
        if ($this->middleware instanceof RequestHandlerInterface) {
            return (new RequestHandlerMiddleware($this->middleware))->process($request, $handler);
        }

        // convert callables to MiddlewareInterface
        if (\is_callable($this->middleware)) {
            return (new CallableMiddlewareDecorator($this->middleware))->process($request, $handler);
        }

        if (\is_array($this->middleware)) {
            $middleware = new MiddlewarePipe();
            foreach ($this->middleware as $class) {
                if (\is_string($class)
                    || \is_array($class)
                    || \is_callable($class)
                    || $class instanceof RequestHandlerInterface
                    || $class instanceof MiddlewareInterface
                ) {
                    $middleware->pipe(new self($this->container, $class));
                    continue;
                }

                $this->throwInvalidMiddlewareException($class);
            }

            return $middleware->process($request, $handler);
        }

        if (! \is_string($this->middleware)) {
            $this->throwInvalidMiddlewareException($this->middleware);
        }

        $instance = $this->container->get($this->middleware);
        return (new self($this->container, $instance))->process($request, $handler);
    }

    private function throwInvalidMiddlewareException(mixed $class): void
    {
        throw new \InvalidArgumentException(sprintf(
            "Unsupported middleware type %s. Allowed types include: %s",
            \get_debug_type($class),
            \implode(', ', ['string', 'array', 'callable', RequestHandlerInterface::class, MiddlewareInterface::class])
        ));
    }
}
