<?php

declare(strict_types=1);

namespace MySchema\Content\Action;

use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use MySchema\Content\Repository\ContentRepository;
use MySchema\Database\ConnectionFactory;
use Psr\Container\ContainerInterface;

class DisplayDashboardAction extends Action
{
    public function __invoke(ContainerInterface $container): ActionResult
    {
        $connection = (new ConnectionFactory($container))->connect();
        $data = (new ContentRepository($connection))
            ->orderBy([
                '' => 'random()',
                'name' => 'asc',
                'created_at' => 'desc'
            ])
            ->limit(30)
            ->fetchAll();
        return new ActionResult($data);
    }

    public function assertAuthorization(): bool
    {
        return true;
    }
}
