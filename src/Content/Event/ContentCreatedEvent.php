<?php

declare(strict_types=1);

namespace MySchema\Content\Event;

use MySchema\Content\Type\Content;

class ContentCreatedEvent
{
    public function __construct(private Content $content, private array $cleanInput)
    {
    }

    public function getContent(): Content
    {
        return $this->content;
    }
}
