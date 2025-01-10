<?php

declare(strict_types=1);

namespace MySchema\Database;

use Psr\Container\ContainerInterface;

final class ConnectionFactory
{
    private Connection $connection;
    private array $databases = [];

    public function __construct(private ContainerInterface $container)
    {
        $this->databases['main'] = $container->get('config')['database']['main'];
        $apps = $container->get('apps');
        foreach ($apps as $app) {
            if (! isset($app['database'])) {
                continue;
            }

            foreach ($app['database'] as $name => $config) {
                if (\array_key_exists($name, $this->databases)) {
                    throw new \InvalidArgumentException(sprintf(
                        "Duplicate database key %s",
                        $name
                    ));
                }

                $this->databases[$name] = $config;
            }
        }
    }

    public function connect(string $connection = 'main'): Connection
    {
        if (! isset($this->connection)) {
            if (! isset($this->databases[$connection])) {
                throw new \InvalidArgumentException(\sprintf(
                    "Database connection %s not found in config",
                    $connection
                ));
            }

            // build the dsn
            $config = $this->databases[$connection];
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
