<?php

declare(strict_types=1);

namespace MySchema\Content\Model\CreativeWork;

use MySchema\Content\Model\Content;
use MySchema\Content\Model\Organization\Organization;
use MySchema\Content\Model\Person\Person;

class CreativeWork extends Content
{
    protected Organization|Person|array $author;
    protected Place|array $contentLocation;
    protected Organization|Person|array $creator;
    protected \DateTime $dateCreated;
    protected \DateTime $dateModified;
    protected Organization|Persion $publisher;
    protected int|string $version;
}
