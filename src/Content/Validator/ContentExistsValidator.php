<?php

declare(strict_types=1);

namespace MySchema\Content\Validator;

use MySchema\Content\Repository\ContentRepository;
use MySchema\Database\Connection;
use function in_array;
use function json_decode;

class ContentExistsValidator
{
    public function __construct(private Connection $connection)
    {
    }

    public function exists(array $params = []): array|bool
    {
        if (empty($params)) {
            return false;
        }

        $repo = new ContentRepository($this->connection);
        if (isset($params['types'])) {
            $repo->types($params['types']);
        }

        if (isset($params['props'])) {
            $repo->props($params['props']);
        }

        $result = $repo->fields(['c.name', 'c.identifier', 'c.props '])->fetch();
        if (is_array($result) && ! empty($result)) {
            return $result;
        }

        return false;
        // $sql = "SELECT ";
        // $query = "SELECT c.id, c.props, ct.data AS types
        //     FROM content c
        //     INNER JOIN content_type ct ON c.id = ct.content_id
        //     WHERE c.identifier = :identifier";
        // $result = $this->connection->fetchAll($query, [
        //     'identifier' => $identifier,
        // ]);

        // if (empty($result)) {
        //     return false;
        // }

        // foreach ($result as $row) {
        //     if (empty($args)) {
        //         return true; // needed to match the identifier only
        //     }

        //     $types = json_decode($row['types'], true);
        //     foreach ($args as $type => $typeProps) {
        //         // filter for similar types
        //         if (! in_array($type, $types)) {
        //             continue;
        //         }

        //         // get props for content item
        //         $props = json_decode($row['props'], true);
        //         return $this->propExists($typeProps['props'] ?? [], $props);
        //     }
        // }

        // return false;
    }

    private function propExists($searchProps, $existingProps): bool
    {
        foreach ($searchProps as $prop => $propData) {
            if (! isset($existingProps[$prop])) {
                continue;
            }

            foreach ($propData as $propKey => $propValue) {
                if (! isset($existingProps[$prop][$propKey])) {
                    continue;
                }

                // recursively search props
                if ($propKey === 'props') {
                    return $this->propExists($propValue, $existingProps[$prop][$propKey]);
                }

                if ($existingProps[$prop][$propKey] == $propValue) {
                    return true;
                }
            }
        }

        return false;
    }
}
