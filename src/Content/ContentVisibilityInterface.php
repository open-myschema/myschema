<?php

declare(strict_types=1);

namespace MySchema\Content;

interface ContentVisibilityInterface
{
    public const int STATUS_PRIVATE = 0;
    public const int STATUS_PUBLIC = 1;
    public const int STATUS_RESTRICTED = 2;
    public const int STATUS_DELETED = 3;
}
