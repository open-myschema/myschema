<?php

declare(strict_types=1);

namespace MySchema\Database;

class Connection
{
    public function __construct(private \PDO $pdo)
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

    public function read(string $query, array $params = []): array
    {
        $stmt = $this->prepareStatement($query, $params);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    public function write(string $query, array $params = []): bool
    {
        $stmt = $this->prepareStatement($query, $params);
        $stmt->execute();

        return $stmt->rowCount() > 0 ? TRUE : FALSE;
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
