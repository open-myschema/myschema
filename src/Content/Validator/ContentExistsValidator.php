<?php

declare(strict_types=1);

namespace MySchema\Content\Validator;

use MySchema\Database\Connection;

class ContentExistsValidator
{
    public function __construct(private Connection $connection)
    {
    }

    public function exists(string $identifier, array $args = []): bool
    {
        // $sql = "SELECT ";
        $query = "SELECT c.props, JSON_AGG(ct.name) AS types
            FROM content c
            INNER JOIN content_type ct ON c.id = ct.content_id
            WHERE c.identifier = :identifier
            GROUP BY c.props";
        $result = $this->connection->fetchAll($query, [
            'identifier' => $identifier,
        ]);

        if (empty($result)) {
            return false;
        }

        foreach ($result as $row) {
            if (empty($args)) {
                return true;
            }

            $props = \json_decode($row['props'], true);
            $types = \json_decode($row['types'], true);
            foreach ($args as $k => $v) {
                if ($k === 'props') {
                    foreach ($v as $prop => $checks) {
                        foreach ($checks as $propName => $propValue) {
                            if (! is_string($propValue)) {
                                continue; // @todo
                            }

                            if (! \array_key_exists($prop, $props)) {
                                continue;
                            }

                            if (isset($props[$prop][$propName])) {
                                return true;
                            }
                        }
                    }
                }

                if ($k === 'types') {
                    foreach ($v as $type) {
                        if (\in_array($type, $types)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
