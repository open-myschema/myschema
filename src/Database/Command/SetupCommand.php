<?php

declare(strict_types=1);

namespace MySchema\Database\Command;

use MySchema\Helper\DatabaseConnectionTrait;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use MySchema\Command\BaseCommand;
use Throwable;

use function explode;
use function strlen;

class SetupCommand extends BaseCommand
{
    use DatabaseConnectionTrait;
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->setDescription("Setup database migrations");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get initial migration
        $connection = $this->getDatabaseConnection();
        $resourceManager = $this->getResourceManager($this->container);
        $migration = $resourceManager->getMigration($connection->getDriver(), 'main::setup-migrations', 'up');

        // execute the migration
        $connection->beginTransaction();
        try {
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

        $io->success("Migration table setup");
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
