<?php

declare(strict_types=1);

namespace MySchema\Server\Listener;

use Laminas\Stratigility\MiddlewarePipe;
use MySchema\EventManager\EventListenerInterface;
use MySchema\Server\Event\HttpRequestEvent;
use MySchema\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

class ServerActionsListener implements EventListenerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListeners(): array
    {
        return [
            HttpRequestEvent::class => [$this, 'onHttpRequestAction'],
        ];
    }

    public function onHttpRequestAction(HttpRequestEvent $event): void
    {
        // prepare the middleware pipeline
        $pipeline = new MiddlewarePipe();
        foreach ($this->container->get('config')['middleware_pipeline'] ?? [] as $middleware) {
            $pipeline->pipe(
                new LazyLoadingMiddleware($this->container, $middleware)
            );
        }

        // handle the response and update the action
        $event->setResponse($pipeline->handle($event->getRequest()));
    }
}
