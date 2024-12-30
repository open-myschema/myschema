<?php

declare(strict_types=1);

namespace MySchema\Server\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    public function configure()
    {
        $this->setName("hello:world");
        $this->setDescription("Introduction component");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Hello, world!");
        return Command::SUCCESS;
    }
}
