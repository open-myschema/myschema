<?php

declare(strict_types=1);

namespace MySchema\Server;

use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;

class Router extends FastRouteRouter
{
    public function match(ServerRequestInterface $request): RouteResult
    {
        return parent::match($request);
    }
}
