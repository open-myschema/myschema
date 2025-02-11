<?php

declare(strict_types=1);

namespace MySchema\Content\Repository;

use MySchema\Content\Thing;
use MySchema\Database\Connection;

use function array_map;
use function implode;
use function is_array;
use function json_decode;
use function substr;

class ContentRepository
{
    private array $created;
    private array $fields;
    private array $groupBy;
    private string $hydrator;
    private string $identifier;
    private int $limit;
    private int $offset;
    private array $orderBy;
    private array $props;
    private array $types;

    public function __construct(private Connection $connection)
    {
    }

    public function created(array $created): static
    {
        $this->created = $created;
        return $this;
    }

    public function fetch(): mixed
    {
        $query = $this->getSelectQuery();
        $result = $this->connection->fetch($query, $this->getSelectParams());
        if (! is_array($result)) {
            return $result;
        }

        $result['tags'] = json_decode($result['tags'], true);
        isset($result['props']) && $result['props'] = json_decode($result['props'], true);
        isset($result['types']) && $result['types'] = json_decode($result['types'], true);
        return $result;
    }

    public function fetchAll(): array
    {
        $query = $this->getSelectQuery();
        $result = $this->connection->fetchAll($query, $this->getSelectParams());
        $processedResult = array_map(function($row): array {
            $row['tags'] = json_decode($row['tags'], true);
            isset($row['props']) && $row['props'] = json_decode($row['props'], true);
            isset($row['types']) && $row['types'] = json_decode($row['types'], true);
            return $row;
        }, $result);
        if (! isset($this->hydrator)) {
            return $processedResult;
        }

        $hydrated = [];
        foreach ($processedResult as $row) {
            $object = new $this->hydrator;
            assert($object instanceof Thing);

            $hydrated[] = $object->hydrate($row);
        }
        return $hydrated;
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

    public function hydrate(string $hydrator): static
    {
        $this->hydrator = $hydrator;
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

    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy(array $orderBy): static
    {
        $this->orderBy = $orderBy;
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

    private function getSelectFields(): string
    {
        $fields = isset($this->fields)
            ? substr(implode(', ', $this->fields), 0, -1)
            : 'c.*';
        if (isset($this->types)) {
            $fields .= ", ct.data AS types";
        }
        $fields .= ", ctg.data AS tags";
        return $fields;
    }

    private function getSelectFrom(): string
    {
        $from = "content c";
        if (isset($this->types)) {
            $from .= " INNER JOIN content_type ct ON c.id = ct.content_id";
        }
        $from .= " LEFT JOIN content_tag ctg ON c.id = ctg.content_id";

        return $from;
    }

    private function getSelectLimit(): string
    {
        $limit = "";
        if (isset($this->limit)) {
            $count = $this->limit;
            $limit = "LIMIT $count";
        }
        return $limit;
    }

    private function getSelectOrderBy(): string
    {
        $orderBy = "";
        if (isset($this->orderBy) && ! empty($this->orderBy)) {
            $orderBy .= "ORDER BY";
            foreach ($this->orderBy as $col => $direction) {
                $orderBy .= " $col $direction,";
            }
            $orderBy = substr($orderBy, 0, -1);
        }
        return $orderBy;
    }

    private function getSelectParams(): array
    {
        $params = [];
        if (isset($this->identifier)) {
            $params['identifier'] = $this->identifier;
        }

        if (isset($this->types)) {
            $params['types'] = implode(', ', $this->types);
        }

        if (isset($this->props)) {
            $params['props'] = json_encode($this->props);
        }

        return $params;
    }

    private function getSelectWhere(): string
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

    private function getSelectQuery(): string
    {
        $fields = $this->getSelectFields();
        $from = $this->getSelectFrom();
        $where = $this->getSelectWhere();
        $orderBy = $this->getSelectOrderBy();
        $limit = $this->getSelectLimit();

        return "SELECT $fields FROM $from $where $orderBy $limit";
    }
}
