<?php

declare(strict_types=1);

namespace MySchema\Content\Type\Event;

class SportsEvent extends Event
{
    protected string $sport;

    public function __construct()
    {
        // set types, tags
        $types = ['main::sports-event'];
        $tags = ['sports event'];

        $this->types = isset($this->types)
            ? $this->types + $types
            : $types;
        $this->tags = isset($this->tags)
            ? $this->tags + $tags
            : $tags;
    }
}
