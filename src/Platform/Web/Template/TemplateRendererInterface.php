<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\Template;

use MySchema\Command\CommandOutputRendererInterface;

interface TemplateRendererInterface extends CommandOutputRendererInterface
{
    public function setTemplate(string $template): void;
}
