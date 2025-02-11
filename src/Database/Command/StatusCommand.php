<?php

declare(strict_types= 1);

namespace MySchema\Database\Command;

use MySchema\Helper\DatabaseConnectionTrait;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use MySchema\Command\BaseCommand;
use Throwable;

use function array_key_exists;

final class StatusCommand extends BaseCommand
{
    use DatabaseConnectionTrait;
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->setDescription('View the status of database schema migrations');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // read migrations status
        $connection = $this->getDatabaseConnection();
        try {
            $data = $connection->read(
                "SELECT database, name, status, executed_at
                FROM migration
                ORDER BY executed_at DESC"
            );
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            return BaseCommand::FAILURE;
        }

        // prepare data
        $reshapedData = [];
        foreach ($data as $row) {
            $reshapedData[$row['name']] = $row;
        }

        $rows = [];
        $resourceManager = $this->getResourceManager($this->container);
        $migrations = $resourceManager->getAllMigrations();
        foreach ($migrations as $name => $config) {
            $rows[] = [
                'migration' => $name,
                'description' => $config['description'] ?? '',
                'status' => array_key_exists($name, $reshapedData) && $reshapedData[$name]['status'] == 1
                    ? 'Done'
                    : 'Pending',
                'executed' => array_key_exists($name, $reshapedData) && $reshapedData[$name]['executed_at'] !== NULL
                    ? $reshapedData[$name]['executed_at']
                    : '',
            ];
        }

        // display status table
        $io->table(['Migration', 'Description', 'Status', 'Executed'], $rows);

        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
