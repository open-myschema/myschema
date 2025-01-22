<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Place;

use MySchema\Content\Model\Content;

class Place extends Content
{
    protected string $address;
    protected string $latitude;
    protected string $logo;
    protected string $longitude;
    protected string $telephone;

    public function __construct()
    {
        // set types, tags
        $types = ['main::place'];
        $tags = ['place'];

        $this->types = isset($this->types)
            ? $this->types + $types
            : $types;
        $this->tags = isset($this->tags)
            ? $this->tags + $tags
            : $tags;
    }
}
