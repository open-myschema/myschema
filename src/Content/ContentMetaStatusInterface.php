<?php

declare(strict_types=1);

namespace MySchema\Content;

interface ContentMetaStatusInterface
{
    public const int STATUS_CONTENT_CREATED = 1;
    public const int STATUS_CONTENT_UPDATED = 2;
    public const int STATUS_CONTENT_DELETED = 3;
    public const int STATUS_TAG_ADDED = 4;
    public const int STATUS_TAG_UPDATED = 5;
    public const int STATUS_TAG_REMOVED = 6;
    public const int STATUS_TYPE_ADDED = 7;
    public const int STATUS_TYPE_UPDATED = 8;
    public const int STATUS_TYPE_REMOVED = 9;
    public const int STATUS_VISIBILITY_UPDATED = 10;
}
