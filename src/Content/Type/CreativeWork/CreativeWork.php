<?php

declare(strict_types=1);

namespace MySchema\Content\Type\CreativeWork;

use MySchema\Content\Type\Content;
use MySchema\Content\Type\Organization\Organization;
use MySchema\Content\Type\Person\Person;
use MySchema\Content\Type\Place\Place;
use DateTime;

class CreativeWork extends Content
{
    protected Organization|Person|array $author;
    protected Place|array $contentLocation;
    protected Organization|Person|array $creator;
    protected DateTime $dateCreated;
    protected DateTime $dateModified;
    protected Organization|Person $publisher;
    protected int|string $version;

    public function __construct()
    {
        // set types, tags
        $this->types = ['main::creative-work'];
        $this->tags = ['creative-work'];
    }
}
