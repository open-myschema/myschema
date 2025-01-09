<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

use MySchema\Content\Model\Person\Person;

class SportsTeam extends SportsOrganization
{
    protected Person|array $athlete;
    protected Person $coach;
}
