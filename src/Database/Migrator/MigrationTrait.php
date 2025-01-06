<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use MySchema\Database\Connection;
use MySchema\Database\ConnectionFactory;

trait MigrationTrait
{
    private function getDatabaseConnection(string $connectionName = 'main'): Connection
    {
        return (new ConnectionFactory($this->container))->connect($connectionName);
    }
}
