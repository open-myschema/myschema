<?php

declare(strict_types= 1);

namespace MySchema\Platform;

use MySchema\Action\ActionResult;

interface AcionResultRendererInterface
{
    public function render(ActionResult $result): string;
}
