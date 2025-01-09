<?php

declare(strict_types=1);

namespace MySchema\Content\Model\Organization;

use MySchema\Content\Model\Content;

class Organization extends Content
{
    protected string $address;
    protected string $email;
    protected string $logo;
    protected string $telephone;

    public function toArray(): array
    {
        $output = parent::toArray();
        isset($this->logo) && $output['props']['logo'] = $this->logo;

        return $output;
    }
}
