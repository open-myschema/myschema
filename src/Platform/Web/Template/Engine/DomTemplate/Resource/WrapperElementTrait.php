<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\Template\Engine\DomTemplate\Resource;

trait WrapperElementTrait
{
    public function getWrapperElement(): Element
    {
        $config = $this->config['wrapper'] ?? [
            'tag' => 'div'
        ];
        return new Element(new ElementConfig($config));
    }
}
