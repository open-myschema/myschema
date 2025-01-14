<?php

declare(strict_types=1);

namespace MySchema\Content\Model;

abstract class Content
{
    protected string $description;
    protected string $identifier;
    protected string $image;
    protected string $name;
    protected int $owner;
    protected array $props;
    protected array $tags;
    protected array $types;
    protected string $url;
    protected int $visibility;

    public function getDescription(): ?string
    {
        return $this->name ?? null;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getImage(): ?string
    {
        return $this->image ?? null;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function hydrate(array $data): static
    {
        $static = new static;

        isset($data['description']) && $static->description = $data['description'];
        isset($data['name']) && $static->name = $data['name'];
        isset($data['identifier']) && $static->identifier = $data['identifier'];
        isset($data['owner']) && $static->owner = $data['owner'];
        isset($data['props']) && $static->props = $data['props'];
        isset($data['tags']) && $static->tags = $data['tags'];
        isset($data['types']) && $static->types = $data['types'];
        isset($data['url']) && $static->url = $data['url'];
        isset($data['visibility']) && $static->visibility = $data['visibility'];

        return $static;
    }

    public function toArray(): array
    {
        $output = [];

        isset($this->description) && $output['description'] = $this->description;
        isset($this->identifier) && $output['identifier'] = $this->identifier;
        isset($this->image) && $output['image'] = $this->image;
        isset($this->name) && $output['name'] = $this->name;
        isset($this->owner) && $output['owner'] = $this->owner;
        isset($this->url) && $output['url'] = $this->url;
        isset($this->props) && $output['props'] = $this->props;
        isset($this->tags) && $output['tags'] = $this->tags;
        isset($this->types) && $output['types'] = $this->types;
        isset($this->visibility) && $output['visibility'] = $this->visibility;
        
        return $output;
    }
}
