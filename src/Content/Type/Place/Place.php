<?php

declare(strict_types=1);

namespace MySchema\Content\Type\Place;

use MySchema\Content\Type\Content;

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
        $this->types = ['main::place'];
        $this->tags = ['place'];
    }
}
