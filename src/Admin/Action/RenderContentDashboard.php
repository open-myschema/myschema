<?php

declare(strict_types=1);

namespace MySchema\Admin\Action;

use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use Psr\Container\ContainerInterface;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;

class RenderContentDashboard extends Action
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
