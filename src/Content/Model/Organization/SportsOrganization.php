<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

class SportsOrganization extends Organization
{
    protected string $sport;

    public function __construct()
    {
        // set types, tags
        $types = ['main::sports-organization'];
        $tags = ['sports organziation'];

        $this->types = isset($this->types)
            ? $this->types + $types
            : $types;
        $this->tags = isset($this->tags)
            ? $this->tags + $tags
            : $tags;
    }

    public function toArray(): array
    {
        $output = parent::toArray();
        isset($this->sport) && $output['props']['sport'] = $this->sport;

        return $output;
    }
}
