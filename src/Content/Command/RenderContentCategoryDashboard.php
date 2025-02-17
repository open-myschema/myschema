<?php

declare(strict_types=1);

namespace MySchema\Content\Action;

use Psr\Container\ContainerInterface;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;

class RenderContentCategoryDashboard extends Action
{
    use ServiceFactoryTrait;

    private const string QUERY_CONTENT_TYPES = 'main::content-types';

    public function __invoke(ContainerInterface $container): ActionResult
    {
        $data = [];
        $connection = (new ConnectionFactory($container))->connect();
        $resources = $this->getResourceManager($container);
        $query = $resources->getQuery($connection->getDriver(), self::QUERY_CONTENT_TYPES);
        $result = $connection->fetchAll($query);
        var_dump($result);
        $data['types'] = \array_map(function ($row) {
            return \json_decode($row['data']);
        }, $result);
        return new ActionResult($data);
    }

    public function assertAuthorization(): bool
    {
        return true;
    }
}
