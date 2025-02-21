<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Router\RouteResult;
use MySchema\Command\CommandOutputRendererInterface;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Helper\ServiceFactoryTrait;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use MySchema\Platform\Web\Event\HtmlRenderedEvent;
use MySchema\Platform\Web\Template\TemplateRendererInterface;
use MySchema\Platform\Web\Template\Engine\DomTemplate\DomTemplateRenderer;
use MySchema\Platform\Web\Template\Engine\Twig\TwigTemplateRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Output\OutputInterface;
use InvalidArgumentException;

use function assert;
use function is_string;
use function sprintf;
use function strpos;

class WebPlatform implements PlatformInterface
{
    use ServiceFactoryTrait;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function formatResponse(ServerRequestInterface $request, OutputInterface $output): ResponseInterface
    {
        // get the relevant renderer
        $renderer = $this->getRenderer($request, $output);

        // get status and headers
        $status = $output instanceof Psr7ResponseOutputInterface
            ? $output->getStatusCode()
            : StatusCodeInterface::STATUS_OK;
        $headers = $output instanceof Psr7ResponseOutputInterface
            ? $output->getHeaders()
            : [];

        // JSON response
        if ($renderer instanceof SimpleJsonRenderer) {
            $data = $renderer->render($output);
            return new JsonResponse($data, $status, $headers);
        }

        // HTML response
        if ($renderer instanceof TemplateRendererInterface) {
            $renderer->setTemplate($this->resolveTemplate($request, $output));
        }

        // render the result
        $html = $renderer->render($output);

        // dispatch html rendered event
        $event = $this->getEventDispatcher($this->container)
            ->dispatch(new HtmlRenderedEvent(
                $request,
                $output,
                $html
            ));
        assert($event instanceof HtmlRenderedEvent);

        return new HtmlResponse($event->getHtml(), $status, $headers);
    }

    private function getRenderer(ServerRequestInterface $request, OutputInterface $result): CommandOutputRendererInterface
    {
        $accept = $request->getHeaderLine('accept');
        if (false !== strpos($accept, 'application/json')) {
            return new SimpleJsonRenderer();
        }

        // resolve the template renderer
        $templateName = $this->resolveTemplate($request, $result);
        $resourceManager = $this->getResourceManager($this->container);
        $templateConfig = $resourceManager->getTemplate($templateName);
        $templateFilename = $templateConfig['filename'];

        // DomTemplate supported formats
        $domTemplateSupported = ['json'];
        foreach ($domTemplateSupported as $supported) {
            if (false !== strpos($templateFilename, $supported)) {
                return $this->container->get(DomTemplateRenderer::class);
            }
        }

        // twig templates
        if (false !== strpos($templateFilename, '.twig')) {
            return $this->container->get(TwigTemplateRenderer::class);
        }

        if ($this->container->has(TemplateRendererInterface::class)) {
            $renderer = $this->container->get(TemplateRendererInterface::class);
            if ($renderer instanceof TemplateRendererInterface) {
                return $renderer;
            }
        }

        throw new InvalidArgumentException(sprintf(
            "Unsupported template format for template %s",
            $templateName
        ));
    }

    private function resolveTemplate(ServerRequestInterface $request, OutputInterface $output, string $default = 'main::error-404'): string
    {
        if ($output instanceof Psr7ResponseOutputInterface && $output->hasTemplate()) {
            return $output->getTemplate();
        }

        $routeResult = $request->getAttribute(RouteResult::class);
        if (! $routeResult instanceof RouteResult || $routeResult->isFailure()) {
            return $default;
        }

        $routeOptions = $routeResult->getMatchedRoute()->getOptions();
        if (! isset($routeOptions['template']) || ! is_string($routeOptions['template'])) {
            return $default;
        }

        return $routeOptions['template'];
    }
}
