<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

use MySchema\Content\Model\Content;

class Organization extends Content
{
    protected string $address;
    protected string $email;
    protected string $logo;
    protected string $telephone;

    public function __construct()
    {
        // set types, tags
        $types = ['main::organization'];
        $tags = ['organization'];

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
        isset($this->logo) && $output['props']['logo'] = $this->logo;

        return $output;
    }
}
