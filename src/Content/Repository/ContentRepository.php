<?php

declare(strict_types=1);

namespace MySchema\Content\Repository;

use MySchema\Database\Connection;

class ContentRepository
{
    private array $fields;
    private array $groupBy;
    private string $identifier;
    private int $limit;
    private array $orderBy;
    private array $props;
    private array $types;

    public function __construct(private Connection $connection)
    {
    }

    public function fetch(int $mode = \PDO::FETCH_ASSOC): mixed
    {
        $fields = $this->getQueryFields();
        $from = $this->getQueryFrom();
        $where = $this->getQueryWhere();
        $orderBy = $this->getQueryOrderBy();
        $limit = $this->getQueryLimit();
        
        $query = "SELECT $fields FROM $from $where $orderBy $limit";
        $result = $this->connection->fetch($query, $this->getQueryParams());
        if (! is_array($result)) {
            return $result;
        }

        $result['tags'] = \json_decode($result['tags'], true);
        isset($result['props']) && $result['props'] = \json_decode($result['props'], true);
        isset($result['types']) && $result['types'] = \json_decode($result['types'], true);
        return $result;
    }

    public function fetchAll(int $mode = \PDO::FETCH_ASSOC): mixed
    {
        $fields = $this->getQueryFields();
        $from = $this->getQueryFrom();
        $where = $this->getQueryWhere();
        $orderBy = $this->getQueryOrderBy();
        $limit = $this->getQueryLimit();
        
        $query = "SELECT $fields FROM $from $where $orderBy $limit";
        $result = $this->connection->fetchAll($query, $this->getQueryParams());
        return \array_map(function($row): array {
            $row['tags'] = \json_decode($row['tags'], true);
            isset($row['props']) && $row['props'] = \json_decode($row['props'], true);
            isset($row['types']) && $row['types'] = \json_decode($row['types'], true);
            return $row;
        }, $result);
    }

    public function fields(array $fields): static
    {
        $this->fields = $fields;
        return $this;
    }

    public function groupBy(array $groupBy): static
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    public function identifier(string $identifier): static
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy(array $orderBy): static
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function prop(string $key, mixed $value): static
    {
        $this->props[$key] = $value;
        return $this;
    }

    public function props(array $props): static
    {
        foreach ($props as $key => $value) {
            $this->props[$key] = $value;
        }

        return $this;
    }

    public function types(array $types): static
    {
        $this->types = $types;
        return $this;
    }

    private function getQueryFields(): string
    {
        $fields = isset($this->fields)
            ? \substr(\implode(', ', $this->fields), 0, -1)
            : 'c.*';
        if (isset($this->types)) {
            $fields .= ", ct.data AS types";
        }
        $fields .= ", ctg.data AS tags";
        return $fields;
    }

    private function getQueryFrom(): string
    {
        $from = "content c";
        if (isset($this->types)) {
            $from .= " INNER JOIN content_type ct ON c.id = ct.content_id";
        }
        $from .= " LEFT JOIN content_tag ctg ON c.id = ctg.content_id";

        return $from;
    }

    private function getQueryLimit(): string
    {
        $limit = "";
        if (isset($this->limit)) {
            $count = $this->limit;
            $limit = "LIMIT $count";
        }
        return $limit;
    }

    private function getQueryOrderBy(): string
    {
        $orderBy = "";
        if (isset($this->orderBy)) {
            $orderBy .= "ORDER BY";
            foreach ($this->orderBy as $col => $direction) {
                $orderBy .= " $col $direction,";
            }
            $orderBy = \substr($orderBy, 0, -1);
        }
        return $orderBy;
    }

    private function getQueryParams(): array
    {
        $params = [];
        if (isset($this->identifier)) {
            $params['identifier'] = $this->identifier;
        }

        if (isset($this->types)) {
            $params['types'] = \implode(', ', $this->types);
        }

        if (isset($this->props)) {
            $params['props'] = \json_encode($this->props);
        }

        return $params;
    }

    private function getQueryWhere(): string
    {
        $where = "";
        if (isset($this->identifier)) {
            $where .= "WHERE c.identifier = :identifier";
        }

        if (isset($this->types)) {
            $where .= $where === ""
                ? "WHERE ct.data ??| ARRAY[:types]"
                : " AND ct.data ??| ARRAY[:types]";
        }

        if (isset($this->props)) {
            $where .= $where === ""
                ? "WHERE c.props @> :props"
                : " AND c.props @> :props";
        }

        return $where;
    }
}
