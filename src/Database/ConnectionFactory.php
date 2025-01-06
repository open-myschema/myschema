<?php

declare(strict_types=1);

namespace MySchema\Database;

use Psr\Container\ContainerInterface;

final class ConnectionFactory
{
    private Connection $connection;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function connect(string $connection = 'main'): Connection
    {
        if (! isset($this->connection)) {
            $configs = $this->container->get('config')['database'];
            if (! isset($configs[$connection])) {
                throw new \InvalidArgumentException(\sprintf(
                    "Database connection %s not found in config",
                    $connection
                ));
            }

            // build the dsn
            $config = $configs[$connection];
            $host = $config['host'] ?? 'localhost';
            $port = $config['port'] ?? 5432;
            $dbname = $config['dbname'] ?? 'myschema';
            $user = $config['user'] ?? '';
            $password = $config['password'] ?? '';
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

            // create the PDO connection
            $pdo = new \PDO($dsn, options: $config['options'] ?? null);
            $this->connection = new Connection($pdo);
        }

        return $this->connection;
    }
}
