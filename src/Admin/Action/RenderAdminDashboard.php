<?php

declare(strict_types=1);

namespace MySchema\Admin\Action;

use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use MySchema\App\AppManager;
use Psr\Container\ContainerInterface;

class RenderAdminDashboard extends Action
{
    public function __invoke(ContainerInterface $container): ActionResult
    {
        $appManager = $container->get(AppManager::class);
        \assert($appManager instanceof AppManager);

        $data = [];
        $data['apps'] = $appManager->getAppsInfo();
        return new ActionResult($data);
    }

    public function assertAuthorization(): bool
    {
        return true;
    }
}
