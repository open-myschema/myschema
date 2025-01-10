<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

use MySchema\Content\Model\Person\Person;

class SportsTeam extends SportsOrganization
{
    protected Person|array $athlete;
    protected Person $coach;

    public function toArray(): array
    {
        $output = parent::toArray();
        isset($this->coach) && $output['props']['coach'] = $this->coach->toArray();

        return $output;
    }
}
