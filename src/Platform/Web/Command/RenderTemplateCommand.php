<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Command;

use Mezzio\Router\RouteResult;
use MySchema\Command\BaseCommand;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function array_map;
use function assert;
use function is_string;
use function json_decode;

use const Fig\Http\Message\StatusCodeInterface\STATUS_NOT_FOUND;

class RenderTemplateCommand extends BaseCommand
{
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->setDescription("Render a HTML template");
        $this->addOption(
            name: 'template',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Template to render'
        );

        $this->addOption(
            name: 'queries',
            mode: InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            description: 'Defined names of queries to execute and attach to template',
            default: []
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = $input->getOptions();

//         var_dump($input);

//         $request = $this->getRequest();
//         $routeResult = $request->getAttribute(RouteResult::class);
//         assert($routeResult instanceof RouteResult);
//         if ($routeResult->isFailure()) {
//             if ($output instanceof Psr7ResponseOutputInterface) {
//                 $output->setData(null, 'null');
//                 $output->setTemplate('main::error-404');
//                 $output->setStatusCode(STATUS_NOT_FOUND);
//             }
//             return BaseCommand::FAILURE;
//         }

        $resourceManager = $this->getResourceManager($this->container);
        $data = [];

        if (isset($options['queries'])) {
            foreach ($options['queries'] as $name => $config) {
                $connection = (new ConnectionFactory($this->container))->connect($config['connection']);
                $query = $resourceManager->getQuery($connection->getDriver(), $config['name']);
                $result = $connection->fetchAll($query, $config['defaults'] ?? []);
                if (! isset($config['json_decode'])) {
                    $data[$name] = $result;
                    continue;
                }

                // process json decodable columns
                array_map(function ($column) use ($result) {
                    foreach ($result as &$row) {
                        foreach ($row as $key => $value) {
                            if ($key === $column && is_string($value)) {
                                $row[$key] = json_decode($value, true);
                            }
                        }
                    }
                }, $config['json_decode']);

                $data[$name] = $result;
            }
        }
        var_dump($data);

        if ($output instanceof Psr7ResponseOutputInterface) {
            $output->setData($data, 'array');
        }
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
