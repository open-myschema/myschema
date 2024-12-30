<?php

declare(strict_types=1);

namespace MySchema\Page;

use Psr\Http\Message\ServerRequestInterface;

class Page
{
    private bool $isFound = FALSE;
    private string $template = 'main::404';
    private string $title = 'Page not found';

    public function __construct(private ServerRequestInterface $request, private array $pagesConfig)
    {
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isFound(): bool
    {
        return $this->isFound;
    }
}
