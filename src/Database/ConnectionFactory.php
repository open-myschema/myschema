<?php

declare(strict_types=1);

namespace MySchema\Database;

use Psr\Container\ContainerInterface;
use InvalidArgumentException;
use PDO;

use function array_key_exists;
use function sprintf;

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
                if (array_key_exists($name, $this->databases)) {
                    throw new InvalidArgumentException(sprintf(
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
                throw new InvalidArgumentException(sprintf(
                    "Database connection %s not found in config",
                    $connection
                ));
            }

            $config = $this->databases[$connection];
            if (! isset($config['dbname'])) {
                throw new InvalidArgumentException(sprintf(
                    "Database name missing from in config"
                ));
            }

            // create the PDO connection
            $dbname = $config['dbname'];
            $pdo = new PDO("sqlite:$dbname", options: $config['options'] ?? null);
            $this->connection = new Connection($pdo);
        }

        return $this->connection;
    }

    public function connectCallback(callable $callback, array $parameters = []): Connection
    {
        return $callback($parameters);
    }
}
