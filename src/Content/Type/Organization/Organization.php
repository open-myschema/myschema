<?php

declare(strict_types=1);

namespace MySchema\Content\Type\Organization;

use MySchema\Content\Type\Content;

class Organization extends Content
{
    protected string $address;
    protected string $email;
    protected string $logo;
    protected string $telephone;

    public function __construct()
    {
        // set types, tags
        $this->types = ['main::organization'];
        $this->tags = ['organization'];
    }

    public function toArray(): array
    {
        $output = parent::toArray();
        isset($this->logo) && $output['props']['logo'] = $this->logo;

        return $output;
    }
}
