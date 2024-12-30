<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RunCommand extends Command
{
    use MigrationTrait;

    private string $name = 'migration:run';

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

            // migrations newly setup
            $io->info("Use command `migration:status` to check pending migrations");
            return Command::SUCCESS;
        }

        // execute queries
        $migrations = $this->generateMigrations();
        $queries = $this->getMigrationSql($migrations);
        foreach ($queries as $database => $databaseQueries) {
            $dbConnection = $this->getDatabaseConnection($database);
            // @todo use a transaction
            foreach ($databaseQueries as $query) {
                $dbConnection->executeStatement($query);
            }
        }
        return Command::SUCCESS;
    }

    public function configure(): void
    {
        $this->setDescription('Generate database schema migrations');
    }
}
