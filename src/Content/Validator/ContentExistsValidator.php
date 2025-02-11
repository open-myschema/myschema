<?php

declare(strict_types=1);

namespace MySchema\Content\Validator;

use MySchema\Content\Repository\ContentRepository;
use MySchema\Database\Connection;

use function is_array;

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
