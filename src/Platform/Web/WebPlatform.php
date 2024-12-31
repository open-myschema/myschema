<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use MySchema\Application\ActionResult;
use MySchema\Application\ServiceFactoryTrait;
use MySchema\Platform\AcionResultRendererInterface;
use MySchema\Platform\PlatformInterface;
use MySchema\Platform\SimpleJsonRenderer;
use MySchema\Platform\Web\Action\HtmlRendererdAction;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebPlatform implements PlatformInterface
{
    use ServiceFactoryTrait;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function formatResponse(
        ServerRequestInterface $request,
        ActionResult $result,
        int $statusCode = StatusCodeInterface::STATUS_OK,
        array $headers = [],
    ): ResponseInterface {
        // get the relevant renderer
        $renderer = $this->getRenderer($request);

        // JSON response
        if ($renderer instanceof JsonRenderer) {
            $data = $renderer->render($result);
            return new JsonResponse($data, $statusCode, $headers);
        }

        // HTML response

        // error response?
        if ($statusCode >= 400) {
            $errorTemplate = $this->getErrorTemplate($statusCode);
            if ($renderer instanceof TemplateRendererInterface) {
                $renderer->setTemplate($errorTemplate);
            }
        }

        // render the result
        $html = $renderer->render($result);

        /** @var HtmlRendererdAction */
        $htmlRenderedEvent = $this->getEventDispatcher($this->container)
            ->dispatch(new HtmlRendererdAction(
                $request,
                $result,
                $html
            ));

        return new HtmlResponse($htmlRenderedEvent->getHtml(), $statusCode, $headers);
    }

    private function getRenderer(ServerRequestInterface $request): AcionResultRendererInterface
    {
        $accept = $request->getHeaderLine('accept');
        if (false !== \strpos($accept, 'application/json')) {
            return new SimpleJsonRenderer();
        }

        // get the template renderer
        $rendererResolver = $this->container->get(TemplateRendererResolverInterface::class);
        if (! $rendererResolver instanceof TemplateRendererResolverInterface) {
            throw new \InvalidArgumentException(\sprintf(
                "Expected an instance of %s, %s given instead",
                TemplateRendererResolverInterface::class,
                \get_debug_type($rendererResolver)
            ));
        }

        return $rendererResolver->resolve($request);
    }

    private function getErrorTemplate(int $statusCode): string
    {
        $errorTemplate = match ($statusCode) {
            StatusCodeInterface::STATUS_UNAUTHORIZED => 'main::error/401.json',
            default => 'main::templates/error/404.json',
        };

        return $errorTemplate;
    }
}
