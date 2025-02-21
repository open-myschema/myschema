<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Command;

use MySchema\Command\BaseCommand;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Helper\DatabaseConnectionTrait;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function array_map;
use function is_string;
use function json_decode;

class RenderTemplateCommand extends BaseCommand
{
    use DatabaseConnectionTrait;
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

        $resourceManager = $this->getResourceManager($this->container);
        $data = [];

        if (isset($options['queries'])) {
            foreach ($options['queries'] as $name => $config) {
                $connection = $this->getDatabaseConnection($config['connection'] ?? 'main');
                $query = $resourceManager->getQuery($config['name']);
                $result = $connection->fetchAll($query, $config['defaults'] ?? []);
                if (! isset($config['json_decode'])) {
                    $data[$name] = $result;
                    continue;
                }

                // process json decodable columns
                array_map(function ($column) use ($result): void {
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

        $output->writeln("Template rendered");
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
