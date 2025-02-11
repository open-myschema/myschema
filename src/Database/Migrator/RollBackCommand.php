<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use MySchema\Command\BaseCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

final class RollBackCommand extends BaseCommand
{
    use MigrationTrait;

    public function configure(): void
    {
        $this->addOption('database', 'd', InputOption::VALUE_OPTIONAL, 'The database to perform migrations', 'main');
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the migration i.e it\'s config key');
        $this->setDescription('Reverse one or more database migrations');
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
            return Command::FAILURE;
        }

        if (! isset($config[$database][$name])) {
            $io->error(sprintf(
                "Migration %s not found in config for database %s migrations",
                $name, $database
            ));
            return Command::FAILURE;
        }

        $migration = $config[$database][$name];
        if (! isset($migration['down'])) {
            $io->error(sprintf(
                "Ensure migration %s on database %s has the down key configured",
                $name, $database
            ));
            return Command::FAILURE;
        }

        $downFile = \getcwd() . $migration['down'];
        if (! \file_exists($downFile)) {
            $io->error(sprintf(
                "Migration down file %s not found",
                $downFile
            ));
            return Command::FAILURE;
        }

        // execute queries
        $connection = $this->getDatabaseConnection($database);
        $contents = \file_get_contents($downFile);
        foreach (\explode(';', $contents) as $sql) {
            if (\strlen($sql) === 0) {
                continue;
            }

            $connection->write($sql);
        }

        // remove migration from database
        $deleteSql = "DELETE FROM migration WHERE name = :name";
        $connection->write($deleteSql, [
            'name' => $name
        ]);

        $io->success(sprintf(
            "Migration %s on database %s successfully rolled back",
            $name, $database
        ));
        return Command::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
