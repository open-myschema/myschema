<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Person;

use MySchema\Content\Model\Content;
use MySchema\Content\Model\Place\Place;

class Person extends Content
{
    protected string $address;
    protected \DateTime $birthDate;
    protected Place $birthPlace;
    protected string $email;
    protected string $givenName;
    protected string $telephone;

    public function __construct()
    {
    }
}
