<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use Psr\Http\Message\ServerRequestInterface;

interface TemplateRendererResolverInterface
{
    public function resolve(ServerRequestInterface $request): TemplateRendererInterface;
}
