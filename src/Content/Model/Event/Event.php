<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Event;

use MySchema\Content\Model\Content;
use MySchema\Content\Model\Organization\Organization;
use MySchema\Content\Model\Place\Place;

class Event extends Content
{
    protected \DateTime $endDate;
    protected Place|string $location;
    protected Organization $organizer;
    protected \DateTime $startDate;

    public function __construct()
    {
    }
}
