<?php

declare(strict_types=1);

namespace MySchema\EventManager;

class Event
{
    protected array $params = [];

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
}
