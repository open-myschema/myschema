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

final class RollBackCommand extends BaseCommand
{
    use DatabaseConnectionTrait;
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->addOption('database', 'd', InputOption::VALUE_OPTIONAL, 'The database to perform migrations', 'main');
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the migration i.e it\'s config key');
        $this->setDescription('Reverse one or more database migrations');
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
        $database = $input->getOption('database');
        $name = $input->getOption('name');

        // get the queries
        $connection = $this->getDatabaseConnection($database);
        $resourceManager = $this->getResourceManager($this->container);
        try {
            $query = $resourceManager->getMigration($connection->getDriver(), $name, 'down');
        } catch (Throwable $e) {
            $io->error($e->getMessage());
            return BaseCommand::FAILURE;
        }

        // execute queries in a transaction
        $connection->beginTransaction();
        try {
            foreach (explode(';', $query) as $sql) {
                if (strlen($sql) === 0) {
                    continue;
                }
                $connection->write($sql);
            }
            $connection->commit();
        } catch (Throwable $e) {
            $connection->rollback();
            $message = $e->getMessage() . "; Rollback aborted without any changes";
            $io->error($message);
            return BaseCommand::FAILURE;
        }

        // remove migration from database
        if ($name !== "main::setup-migrations") {
            $deleteSql = "DELETE FROM migration WHERE name = :name";
            $connection->write($deleteSql, [
                'name' => $name
            ]);
        }

        $io->success(sprintf(
            "Migration %s on database %s successfully rolled back",
            $name, $database
        ));
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
