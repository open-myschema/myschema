<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

use MySchema\Content\Model\Person\Person;

class SportsTeam extends SportsOrganization
{
    protected Person|array $athlete;
    protected Person $coach;

    public function __construct()
    {
        // set types, tags
        $types = ['main::sports-team'];
        $tags = ['sports team'];

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
        isset($this->coach) && $output['props']['coach'] = $this->coach->toArray();

        return $output;
    }
}
