<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Router\RouteResult;
use MySchema\Action\ActionResult;
use MySchema\Helper\ServiceFactoryTrait;
use MySchema\Platform\AcionResultRendererInterface;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use MySchema\Platform\Web\Action\HtmlRenderedAction;
use MySchema\Platform\Web\DomTemplate\DomTemplateRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebPlatform implements PlatformInterface
{
    use ServiceFactoryTrait;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function formatResponse(ServerRequestInterface $request, ActionResult $result): ResponseInterface
    {
        // get the relevant renderer
        $renderer = $this->getRenderer($request, $result);

        // JSON response
        if ($renderer instanceof SimpleJsonRenderer) {
            $data = $renderer->render($result);
            return new JsonResponse($data, $result->getCode(), $result->getHeaders());
        }

        // HTML response
        if ($renderer instanceof TemplateRendererInterface) {
            $renderer->setTemplate($this->resolveTemplate($request, $result));
        }

        // render the result
        $html = $renderer->render($result);

        /** @var HtmlRenderedAction */
        $htmlRenderedEvent = $this->getEventDispatcher($this->container)
            ->dispatch(new HtmlRenderedAction(
                $request,
                $result,
                $html
            ));

        return new HtmlResponse($htmlRenderedEvent->getHtml(), $result->getCode(), $result->getHeaders());
    }

    private function getRenderer(ServerRequestInterface $request, ActionResult $result): AcionResultRendererInterface
    {
        $accept = $request->getHeaderLine('accept');
        if (false !== \strpos($accept, 'application/json')) {
            return new SimpleJsonRenderer();
        }

        // resolve the template renderer
        $template = $this->resolveTemplate($request, $result);
        $config = $this->container->get('config')['resources']['templates'] ?? [];
        $templateName = $config[$template] ?? '';

        // DomTemplate supported formats
        $domTemplateSupported = ['json'];
        foreach ($domTemplateSupported as $supported) {
            if (FALSE !== \strpos($templateName, $supported)) {
                return $this->container->get(DomTemplateRenderer::class);
            }
        }

        if ($this->container->has(TemplateRendererInterface::class)) {
            $renderer = $this->container->get(TemplateRendererInterface::class);
            if ($renderer instanceof TemplateRendererInterface) {
                return $renderer;
            }
        }

        throw new \InvalidArgumentException(\sprintf(
            "Unsupported template format for template %s",
            $template
        ));
    }

    private function resolveTemplate(ServerRequestInterface $request, ActionResult $result, string $default = 'admin::error-404'): string
    {
        if ($result->hasTemplate()) {
            return $result->getTemplate();
        }

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
