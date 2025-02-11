<?php

declare(strict_types=1);

namespace MySchema\Command;

use Symfony\Component\Console\Command\Command;
use Psr\Container\ContainerInterface;

abstract class BaseCommand extends Command
{
    public const FAILURE = Command::FAILURE;
    public const SUCCESS = Command::SUCCESS;

    public function __construct(protected ContainerInterface $container, string $name)
    {
        parent::__construct($name);
    }

    abstract public function isAuthorized(): bool;
}
