<?php

declare(strict_types=1);

namespace MySchema\Content\Type\Person;

use MySchema\Content\Type\Place\Place;
use MySchema\Content\Type\Content;
use DateTime;

class Person extends Content
{
    protected string $address;
    protected DateTime $birthDate;
    protected Place $birthPlace;
    protected string $email;
    protected string $givenName;
    protected string $telephone;

    public function __construct()
    {
        // set types, tags
        $this->types = ['main::person'];
        $this->tags = ['person'];
    }
}
