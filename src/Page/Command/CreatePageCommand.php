<?php

declare(strict_types=1);

namespace MySchema\Page\Command;

use MySchema\Page\Action\CreatePageAction;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreatePageCommand extends Command
{
    private string $name = "create:page";

    public function __construct(private ContainerInterface $container)
    {
        parent::__construct($this->name);
    }

    public function configure(): void
    {
        $this->setDescription('Create a new page');
        $this->setHelp("Create a new page by specifying it's title, description and URL path");
        $this->addOption('title', 't', InputOption::VALUE_REQUIRED, "The page's title");
        $this->addOption('description', 'd', InputOption::VALUE_REQUIRED, "The page's description");
        $this->addOption('url', 'u', InputOption::VALUE_REQUIRED, "The page's URL page e.g '/contact-us'");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // confirm input
        if (
            ! $input->hasOption('title')
            || ! $input->hasOption('description')
            || ! $input->hasOption('url')
        ) {
            $io->error("Missing argument. Ensure title, description and url are set");
            return Command::FAILURE;
        }

        // execute action
        $action = new CreatePageAction;
        $action->setParams([
            'title' => $input->getOption('title'),
            'description' => $input->getOption('description'),
            'url' => $input->getOption('url'),
        ]);
        $result = $action($this->container);

        if (TRUE !== $result->getData()) {
            $io->error($result->getMessage());
            return Command::FAILURE;
        }

        $io->success($result->getMessage());
        return Command::SUCCESS;
    }
}
