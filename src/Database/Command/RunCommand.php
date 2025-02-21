<?php

declare(strict_types= 1);

namespace MySchema\Database\Command;

use MySchema\Command\BaseCommand;
use MySchema\Command\Validator\CommandInputValidator;
use MySchema\Helper\DatabaseConnectionTrait;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function array_values;
use function explode;
use function sprintf;
use function strlen;
use function time;

final class RunCommand extends BaseCommand
{
    use DatabaseConnectionTrait;
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->addOption(
            name: 'connection',
            shortcut: 'c',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'The connection to perform migrations',
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

        // validate the input
        $validator = new CommandInputValidator($this->getDefinition());
        if (! $validator->isValid($input->getOptions() + $input->getArguments())) {
            $io->error(array_values($validator->getMessages()));
            return BaseCommand::FAILURE;
        }

        // get details
        $connectionName = $input->getOption('connection');
        $name = $input->getOption('name');

        // get the database connection
        try {
            $connection = $this->getDatabaseConnection($connectionName);
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            return BaseCommand::FAILURE;
        }

        // execute queries
        $connection->beginTransaction();
        $resourceManager = $this->getResourceManager($this->container);
        try {
            $migration = $resourceManager->getMigration($name, 'up');
            foreach (explode(';', $migration) as $sql) {
                if (strlen($sql) === 0) {
                    continue;
                }

                $connection->write($sql);
            }
            $connection->commit();
        } catch (Throwable $e) {
            $connection->rollback();
            $io->error($e->getMessage());
            return BaseCommand::FAILURE;
        }

        // @todo actually run this query below first before the migration itself?
        // if this query fails, rollback and do not proceed
        // if migration fails at any point, also rollback this query..

        // update migration table
        $insertMigration = "INSERT INTO migration (connection, name, status, executed_at)
            VALUES(:connection, :name, :status, :executed_at)";
        $connection->write($insertMigration, [
            'connection' => $connectionName,
            'name' => $name,
            'status' => 1,
            'executed_at' => time(),
        ]);

        $io->success(sprintf(
            "Migration %s on database %s successfully run",
            $name, $connectionName
        ));
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
