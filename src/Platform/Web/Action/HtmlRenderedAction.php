<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Action;

use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use Psr\Http\Message\ServerRequestInterface;

class HtmlRenderedAction extends Action
{
    public function __construct(
        private ServerRequestInterface $request,
        private ActionResult $actionResult,
        private string $html
    ) {
    }

    public function assertAuthorization(): bool
    {
        return TRUE;
    }

    public function getActionResult(): ActionResult
    {
        return $this->actionResult;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setHtml(string $html): void
    {
        $this->html = $html;
    }
}
