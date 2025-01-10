<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

class SportsOrganization extends Organization
{
    protected string $sport;

    public function toArray(): array
    {
        $output = parent::toArray();
        isset($this->sport) && $output['props']['sport'] = $this->sport;

        return $output;
    }
}
