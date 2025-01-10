<?php

declare(strict_types=1);

namespace MySchema\Content\Model;

class Content
{
    protected string $description;
    protected int $id;
    protected string $identifier;
    protected string $image;
    protected array $meta;
    protected string $name;
    protected int $owner;
    protected array $props;
    protected array $tags;
    protected array $types;
    protected string $url;
    protected int $visibility;

    public function toArray(): array
    {
        $output = [];
        isset($this->description) && $output['description'] = $this->description;
        isset($this->id) && $output['id'] = $this->id;
        isset($this->identifier) && $output['identifier'] = $this->identifier;
        isset($this->image) && $output['image'] = $this->image;
        isset($this->name) && $output['name'] = $this->name;
        isset($this->owner) && $output['owner'] = $this->owner;
        isset($this->url) && $output['url'] = $this->url;
        isset($this->props) && $output['props'] = $this->props;
        isset($this->meta) && $output['meta'] = $this->meta;
        isset($this->tags) && $output['tags'] = $this->tags;
        isset($this->types) && $output['types'] = $this->types;
        isset($this->visibility) && $output['visibility'] = $this->visibility;
        
        return $output;
    }
}
