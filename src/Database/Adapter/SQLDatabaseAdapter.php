<?php

declare(strict_types=1);

namespace MySchema\Database\Adapter;

use Doctrine\DBAL\Statement;
use MySchema\Database\Connection;
use MySchema\Database\DatabaseInterface;

class SQLDatabaseAdapter implements DatabaseInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function read(string $query, array $params = []): array
    {
        $stmt = $this->prepareStatement($query, $params);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function write(string $query, array $params = []): bool
    {
        $stmt = $this->prepareStatement($query, $params);
        $result = $stmt->executeStatement();

        return \is_int($result) || \is_numeric($result) ? TRUE : FALSE;
    }

    private function prepareStatement(string $sql, array $params): Statement
    {
        $stmt = $this->connection->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        return $stmt;
    }
}
