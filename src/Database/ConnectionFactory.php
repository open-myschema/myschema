<?php

declare(strict_types=1);

namespace MySchema\Database;

use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

final class ConnectionFactory
{
    private Connection $connection;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function connect(string $connection): Connection
    {
        if (! isset($this->connection)) {
            $config = $this->container->get('config')['database'];
            if (! isset($config[$connection])) {
                throw new \InvalidArgumentException(\sprintf(
                    "Database connection %s not found in config",
                    $connection
                ));
            }

            $config[$connection]['wrapperClass'] = Connection::class;
            $this->connection = DriverManager::getConnection($config[$connection]);
        }

        return $this->connection;
    }
}
