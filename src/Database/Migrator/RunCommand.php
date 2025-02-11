<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use MySchema\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function explode;
use function getcwd;
use function file_exists;
use function file_get_contents;
use function sprintf;
use function strlen;

final class RunCommand extends BaseCommand
{
    use MigrationTrait;

    public function configure(): void
    {
        $this->addOption(
            name: 'database',
            shortcut: 'd',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'The database to perform migrations',
            default: 'main'
        );
        $this->addOption(
            name: 'name',
            shortcut: null,
            mode: InputOption::VALUE_REQUIRED,
            description: 'The name of the migration i.e it\'s config key'
        );
        $this->setDescription('Execute one or more pending migrations');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get details
        $database = $input->getOption('database');
        $name = $input->getOption('name') ?? '';
        $config = $this->container->get('config')['migrations'];
        if (! isset($config[$database])) {
            $io->error(sprintf("Database %s not found in migrations config", $database));
            return BaseCommand::FAILURE;
        }

        if (! isset($config[$database][$name])) {
            $io->error(sprintf(
                "Migration %s not found in config for database %s migrations",
                $name, $database
            ));
            return BaseCommand::FAILURE;
        }

        $migration = $config[$database][$name];
        if (! isset($migration['up']) || ! isset($migration['down'])) {
            $io->error(sprintf(
                "Ensure migration %s on database %s has both up and down keys configured",
                $name, $database
            ));
            return BaseCommand::FAILURE;
        }

        $upFile = getcwd() . $migration['up'];
        $downFile = getcwd() . $migration['down'];
        if (! file_exists($upFile)) {
            $io->error(sprintf(
                "Migration up file %s not found",
                $upFile
            ));
            return BaseCommand::FAILURE;
        }
        if (! file_exists($downFile)) {
            $io->error(sprintf(
                "Migration down file %s not found",
                $downFile
            ));
            return BaseCommand::FAILURE;
        }

        // execute queries
        $connection = $this->getDatabaseConnection($database);
        $contents = file_get_contents($upFile);
        $connection->beginTransaction();
        try {
            foreach (explode(';', $contents) as $sql) {
                if (strlen($sql) === 0) {
                    continue;
                }

                $connection->write($sql);
            }
            $connection->commit();
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            $connection->rollback();
            return BaseCommand::FAILURE;
        }

        // update migration table
        $insertMigration = "INSERT INTO migration (database, name, description, status)
            VALUES(:database, :name, :description, :status)";
        $connection->write($insertMigration, [
            'database' => $database,
            'name' => $name,
            'description' => $migration['description'] ?? '',
            'status' => 1
        ]);

        $io->success(sprintf(
            "Migration %s on database %s successfully run",
            $name, $database
        ));
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
