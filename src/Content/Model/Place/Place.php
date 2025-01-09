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
}
