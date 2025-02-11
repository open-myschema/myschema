<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use MySchema\Command\BaseCommand;

use function array_key_exists;
use function array_keys;

final class StatusCommand extends BaseCommand
{
    use MigrationTrait;

    public function configure(): void
    {
        $this->setDescription('View the status of database schema migrations');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $query = "SELECT database, name, description, status, executed_at FROM migration";
        $data = $this->getDatabaseConnection()->read($query);
        $reshapedData = [];
        foreach ($data as $row) {
            $reshapedData[$row['database'] . '-' . $row['name']] = $row;
        }

        $rows = [];
        $config = $this->container->get('config')['migrations'];
        foreach ($config as $database => $migrations) {
            foreach (array_keys($migrations) as $migration) {
                $key = "$database-$migration";
                if ($key === "main-initial") {
                    continue;
                }

                $rows[] = [
                    'database' => $database,
                    'migration' => $migration,
                    'description' => $migrations[$migration]['description'] ?? '',
                    'status' => array_key_exists($key, $reshapedData) && $reshapedData[$key]['status'] == 1
                        ? 'Done'
                        : 'Pending',
                    'executed' => array_key_exists($key, $reshapedData) && $reshapedData[$key]['executed_at'] !== NULL
                        ? $reshapedData[$key]['executed_at']
                        : '',
                ];
            }
        }

        // display status table
        $io->table(['Database', 'Migration', 'Description', 'Status', 'Executed'], $rows);

        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
