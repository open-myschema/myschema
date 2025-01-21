<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Action;

use Fig\Http\Message\StatusCodeInterface;
use Mezzio\Router\RouteResult;
use MySchema\Action\Action;
use MySchema\Action\ActionResult;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\RequestActionHelper;
use MySchema\Helper\ServiceFactoryTrait;
use Psr\Container\ContainerInterface;

class RenderTemplateAction extends Action
{
    use RequestActionHelper;
    use ServiceFactoryTrait;

    public function __invoke(ContainerInterface $container): ActionResult
    {
        $request = $this->getRequest();
        $routeResult = $request->getAttribute(RouteResult::class);
        \assert($routeResult instanceof RouteResult);
        if ($routeResult->isFailure()) {
            return new ActionResult(
                status: StatusCodeInterface::STATUS_NOT_FOUND,
                template: 'main::error-404'
            );
        }

        $resourceManager = $this->getResourceManager($container);
        $routeOptions = $routeResult->getMatchedRoute()->getOptions();
        $data = [];

        if (isset($routeOptions['queries'])) {
            $connection = (new ConnectionFactory($container))->connect('phoenix');
            foreach ($routeOptions['queries'] as $name => $config) {
                $query = $resourceManager->getQuery($connection->getDriver(), $config['name']);
                $result = $connection->fetchAll($query, $config['defaults'] ?? []);
                if (! isset($config['json_decode'])) {
                    $data[$name] = $result;
                    continue;
                }

                // process json decodable columns
                \array_map(function ($column) use ($result) {
                    foreach ($result as &$row) {
                        foreach ($row as $key => $value) {
                            if ($key === $column && \is_string($value)) {
                                $row[$key] = \json_decode($value, true);
                            }
                        }
                    }
                }, $config['json_decode']);

                $data[$name] = $result;
            }
        }

        return new ActionResult($data);
    }

    public function assertAuthorization(): bool
    {
        return true;
    }
}
