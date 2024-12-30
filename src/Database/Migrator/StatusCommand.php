<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class StatusCommand extends Command
{
    use MigrationTrait;

    private string $name = "migration:status";

    public function __construct(private ContainerInterface $container)
    {
        parent::__construct($this->name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // check migration table existence
        $connection = $this->getDatabaseConnection();
        if (! $connection->createSchemaManager()->tableExists('migration')) {
            if (! $this->setupMigrations()) {
                $io->error('Error setting up migrations');
                return Command::FAILURE;
            }
        }

        // get changes in schema between existing databases and code defined models
        $migrations = $this->generateMigrations();
        $queries = $this->getMigrationSql($migrations);
        $rows = [];
        foreach ($queries as $database => $databaseQueries) {
            foreach ($databaseQueries as $query) {
                $rows[] = [$database, $query];
            }
        }
        $io->table(['Database', 'Pending Migrations'], $rows);
        $io->text("Use command `migration:run` to execute these migrations");

        return Command::SUCCESS;
    }

    public function configure(): void
    {
        $this->setDescription('View the status of database schema migrations');
    }
}
