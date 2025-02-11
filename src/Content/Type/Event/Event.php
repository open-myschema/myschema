<?php

declare(strict_types=1);

namespace MySchema\Content\Type\Event;

use MySchema\Content\Type\Organization\Organization;
use MySchema\Content\Type\Place\Place;
use MySchema\Content\Type\Content;
use DateTime;

class Event extends Content
{
    protected DateTime $endDate;
    protected Place|string $location;
    protected Organization $organizer;
    protected DateTime $startDate;

    public function __construct()
    {
        // set types, tags
        $this->types = ['main::event'];
        $this->tags = ['event'];
    }
}
