<?php

declare(strict_types=1);

namespace MySchema\Database;

class Connection
{
    public function __construct(private \PDO $pdo, private string $driver)
    {
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function fetch(string $query, array $params = [], int $mode = \PDO::FETCH_ASSOC): mixed
    {
        $stmt = $this->prepareStatement($query, $params);
        $stmt->execute();
        return $stmt->fetch($mode);
    }

    public function fetchAll(string $query, array $params = [], int $mode = \PDO::FETCH_ASSOC): mixed
    {
        return $this->read($query, $params, $mode);
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function insert(string $query, array $params = []): bool|string
    {
        $this->write($query, $params);
        return $this->pdo->lastInsertId();
    }

    public function read(string $query, array $params = [], int $mode = \PDO::FETCH_ASSOC): mixed
    {
        $stmt = $this->prepareStatement($query, $params);
        $stmt->execute();
        return $stmt->fetchAll($mode);
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    public function write(string $query, array $params = []): int
    {
        $stmt = $this->prepareStatement($query, $params);
        $stmt->execute();

        return $stmt->rowCount();
    }

    private function prepareStatement(string $sql, array $params): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        return $stmt;
    }
}
