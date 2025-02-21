<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\Template\Engine\DomTemplate\Resource;

class Block
{
    private array $elements = [];

    public function __construct(private BlockConfig $config)
    {
        foreach ($config->getElements() as $elementDefinition) {
            $this->elements[] = new Element(new ElementConfig($elementDefinition));
        }
    }

    public function render(\Dom\HTMLDocument $document, array $params = []): \Dom\HTMLElement
    {
        // create a wrapper
        $wrapper = $this->config->getWrapperElement();
        $block = $document->createElement($wrapper->getTag());

        // append elements to the block
        foreach ($this->elements as $elements) {
            $block->appendChild($elements->render($document, $params));
        }

        return $block;
    }
}
