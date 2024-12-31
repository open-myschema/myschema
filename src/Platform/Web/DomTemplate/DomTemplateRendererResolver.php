<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use MySchema\Platform\Web\TemplateRendererInterface;
use MySchema\Platform\Web\TemplateRendererResolverInterface;
use MySchema\Platform\Web\TemplateRendererResolverTrait;
use Psr\Http\Message\ServerRequestInterface;

class DomTemplateRendererResolver implements TemplateRendererResolverInterface
{
    use TemplateRendererResolverTrait;

    public function resolve(ServerRequestInterface $request): TemplateRendererInterface
    {
        // get the template name from the request
        $template = $this->getTemplateNameFromRequest($request);

        // build the renderer
        $adapter = new LocalFilesystemAdapter(getcwd() . '/resources/');
        $filesystem = new Filesystem($adapter);

        $renderer = new DomTemplateRenderer($filesystem);
        $renderer->setTemplate($template);

        return $renderer;
    }
}
