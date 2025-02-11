<?php

declare(strict_types=1);

namespace MySchema\Database\Migrator;

use MySchema\Database\ConnectionFactory;
use MySchema\Helper\ServiceFactoryTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use MySchema\Command\BaseCommand;

class SetupCommand extends BaseCommand
{
    use ServiceFactoryTrait;

    public function configure(): void
    {
        $this->setDescription("Setup database migrations");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get initial migration
        $config = $this->container->get('config')['migrations'];
        $migration = $config['main']['initial'];

        $connectionFactory = new ConnectionFactory($this->container);
        $connection = $connectionFactory->connect();

        $upFile = \getcwd() . $migration['up'];
        $contents = \file_get_contents($upFile);
        foreach (\explode(';', $contents) as $sql) {
            if (\strlen($sql) === 0) {
                continue;
            }

            $connection->write($sql);
        }

        $io->success("Migration table setup");
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
