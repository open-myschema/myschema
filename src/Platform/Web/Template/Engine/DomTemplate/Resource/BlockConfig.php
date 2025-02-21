<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\Template\Engine\DomTemplate\Resource;

class BlockConfig
{
    use WrapperElementTrait;

    public function __construct(private array $config)
    {
    }

    public function getElements(): array
    {
        return $this->config['elements'] ?? [];
    }
}
