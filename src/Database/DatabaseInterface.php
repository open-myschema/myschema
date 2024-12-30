<?php

declare(strict_types=1);

namespace MySchema\Database;

interface DatabaseInterface
{
    public function read(string $query, array $params = []): array;
    public function write(string $query, array $params = []): bool;
}
