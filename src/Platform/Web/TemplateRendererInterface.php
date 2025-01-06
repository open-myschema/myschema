<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web;

use MySchema\Platform\AcionResultRendererInterface;

interface TemplateRendererInterface extends AcionResultRendererInterface
{
    public function setTemplate(string $template): void;
}
