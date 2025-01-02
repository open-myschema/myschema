<?php

declare(strict_types=1);

namespace MySchema\Server\Action;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Stratigility\MiddlewarePipe;
use MySchema\Action\ActionHandlerInterface;
use MySchema\Server\Action\HttpRequestAction;
use MySchema\Server\Middleware\LazyLoadingMiddleware;
use Psr\Container\ContainerInterface;

class ServerActionsListener implements ActionHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListeners(): array
    {
        return [
            HttpRequestAction::class => [$this, 'onHttpRequestAction'],
        ];
    }

    public function onHttpRequestAction(HttpRequestAction $action): void
    {
        // prepare the middleware pipeline
        $pipeline = new MiddlewarePipe();
        foreach ($this->container->get('config')['middleware_pipeline'] ?? [] as $middleware) {
            $pipeline->pipe(
                new LazyLoadingMiddleware($this->container, $middleware)
            );
        }

        // check authorization
        if (! $action->assertAuthorization()) {
            $action->setResponse(
                (new ResponseFactory())->createResponse(
                    code: StatusCodeInterface::STATUS_UNAUTHORIZED
                )
            );
            return;
        }

        // handle the response and update the action
        $action->setResponse($pipeline->handle($action->getRequest()));
    }
}
