<?php

declare(strict_types=1);

namespace MySchema\Database;

use MySchema\Database\Adapter\PostgresAdapter;
use MySchema\Database\Adapter\SQLiteAdapter;

class Connection extends \Doctrine\DBAL\Connection
{
    public function getAdapter(): DatabaseInterface
    {
        $params = $this->getParams();
        return match ($params['driver'] ?? 'pdo_sqlite') {
            'pdo_pgsql', 'pgsql' => new PostgresAdapter($this),
            default => new SQLiteAdapter($this)
        };
    }
}
