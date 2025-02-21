<?php

declare(strict_types= 1);

namespace MySchema\Helper;

use MySchema\Database\Connection;
use MySchema\Database\ConnectionFactory;

trait DatabaseConnectionTrait
{
    private function getDatabaseConnection(string $connectionName = 'main'): Connection
    {
        return (new ConnectionFactory($this->container))->connect($connectionName);
    }
}
