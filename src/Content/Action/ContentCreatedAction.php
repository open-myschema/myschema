<?php

declare(strict_types=1);

namespace MySchema\Content\Action;

use MySchema\Action\Action;
use MySchema\Content\Model\Content;

class ContentCreatedAction extends Action
{
    public function __construct(private Content $content, private array $cleanInput)
    {
    }

    public function assertAuthorization(): bool
    {
        return TRUE;
    }

    public function getContent(): Content
    {
        return $this->content;
    }
}
