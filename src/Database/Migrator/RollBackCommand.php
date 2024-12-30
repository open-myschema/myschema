<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RollBackCommand extends Command
{
    private string $name = 'migration:rollback';

    public function __construct(private ContainerInterface $container)
    {
        parent::__construct($this->name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        return Command::SUCCESS;
    }

    public function configure(): void
    {
        $this->setDescription('Reverse one or more database schema migrations');
    }
}
