<?php

declare(strict_types=1);

namespace MySchema\Application;

use Psr\Container\ContainerInterface;

abstract class Action
{
    protected array $params = [];

    public function __invoke(ContainerInterface $container): ActionResult
    {
        throw new \InvalidArgumentException(\sprintf(
            "Action %s is not invokable",
            static::class
        ));
    }

    public function getParam(string $param, mixed $default = NULL): mixed
    {
        if (! $this->hasParam($param)) {
            return $default;
        }

        return $this->params[$param];
    }

    public function hasParam(string $param): bool
    {
        return isset($this->params[$param]);
    }

    public function setParam(string $param, mixed $value): void
    {
        $this->params[$param] = $value;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    abstract public function assertAuthorization(): bool;

    abstract public function isValid(): bool;
}
