<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\Template\Engine\DomTemplate\Resource;

class ElementConfig
{
    public function __construct(private array $config)
    {
        if (! isset($this->config['tag'])) {
            throw new \InvalidArgumentException('Element config does not contain a tag');
        }
    }

    public function getAttributes(): array
    {
        return $this->config['attributes'] ?? [];
    }

    public function getChildren(): array
    {
        return $this->config['children'] ?? [];
    }

    public function getTag(): string
    {
        return $this->config['tag'];
    }

    public function getStyle(): array
    {
        return $this->config['attributes']['style'] ?? [];
    }

    public function getValue(): string
    {
        return $this->config['value'] ?? '';
    }

    public function hasChildren(): bool
    {
        return isset($this->config['children'])
            && is_array($this->config['children'])
            && ! empty($this->config['children']);
    }

    public function hasStyle(): bool
    {
        $attributes = $this->getAttributes();
        if (isset($attributes['style']) && is_array($attributes['style'])) {
            return true;
        }
        return false;
    }

    public function hasValue(): bool
    {
        return isset($this->config['value']) && $this->config['value'] !== '';
    }
}
