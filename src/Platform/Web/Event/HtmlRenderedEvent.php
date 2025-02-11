<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Event;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HtmlRenderedEvent
{
    public function __construct(
        private ServerRequestInterface $request,
        private OutputInterface $output,
        private string $html
    ) {
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
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
