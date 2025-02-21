<?php

declare(strict_types=1);

namespace MySchema\App\Command;

use MySchema\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MySchema\Command\Output\Psr7ResponseOutput;

class RenderHomePageCommand extends BaseCommand
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $apps = $this->container->get('apps');
        $data = [];
        foreach ($apps as $app) {
            if (! isset($app['info'])) {
                continue;
            }

            $data[] = $app['info'];
        }

        if ($output instanceof Psr7ResponseOutput) {
            $output->setData([
                'apps' => $data,
            ], 'array');
        }
        return BaseCommand::SUCCESS;
    }

    public function isAuthorized(): bool
    {
        return true;
    }
}
