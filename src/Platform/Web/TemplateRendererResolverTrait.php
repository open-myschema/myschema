<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Mezzio\Router\RouteResult;
use Psr\Http\Message\ServerRequestInterface;

trait TemplateRendererResolverTrait
{
    protected function getTemplateNameFromRequest(ServerRequestInterface $request, string $default = 'main::error/404.json'): string
    {
        $routeResult = $request->getAttribute(RouteResult::class);
        if (! $routeResult instanceof RouteResult || $routeResult->isFailure()) {
            return $default;
        }

        $routeOptions = $routeResult->getMatchedRoute()->getOptions();
        if (! isset($routeOptions['template']) || ! \is_string($routeOptions['template'])) {
            return $default;
        }

        return $routeOptions['template'];
    }
}
