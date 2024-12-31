<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate\Resource;

use function array_key_exists;
use function count;

final class Element
{
    private const array VALID_ELEMENTS = [
        // embedded content
        'embed', 'fencedframe', 'iframe', 'mathml', 'object', 'picture', 'portal', 'source', 'svg',

        // form content
        'button', 'datalist', 'fieldset', 'form', 'input', 'label', 'legend', 'meter', 'optgroup',
        'option', 'progress', 'select', 'textarea',

        // inline text
        'a', 'abbr', 'b', 'bdi', 'bdo', 'br', 'cite', 'code', 'data', 'dfn', 'em', 'i', 'kbd', 'mark',
        'q', 'rp', 'rt', 'ruby', 's', 'samp', 'small', 'span', 'strong', 'sub', 'sup', 'time', 'u', 'var', 'wbr',

        // interactive
        'details', 'dialog', 'summary',

        // media
        'area', 'audio', 'img', 'map', 'track', 'video',

        // scripting
        'canvas', 'noscript', 'script',

        // sectioning
        'address', 'article', 'aside', 'footer', 'header', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hgroup', 'main',
        'nav', 'search', 'section',
        

        // table
        'caption', 'col', 'colgroup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr',

        // text content
        'blockquote', 'dd', 'div', 'dl', 'dt', 'figcaption', 'figure', 'hr', 'li', 'menu', 'ol', 'p', 'pre', 'ul',
    ];

    public function __construct(protected ElementConfig $config)
    {
        if (! in_array($config->getTag(), self::VALID_ELEMENTS, true)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid or unsupported HTML element %s",
                $config->getTag(),
            ));
        }
    }

    public function render(\Dom\HTMLDocument $document, array $params = []): \Dom\HTMLElement
    {
        // create the element
        $element = $document->createElement($this->config->getTag());

        // set attributes and value
        $this->setAttributes($element, $params);
        $this->setValue($element, $params);
        $this->setChildren($document, $element, $params);

        return $element;
    }

    public function getTag(): string
    {
        return $this->config->getTag();
    }

    private function setAttributes(\Dom\HTMLElement $element, array $params): void
    {
        $attributes = [];
        if ($this->config->hasStyle()) {
            $styles = '';
            foreach ($this->config->getStyle() as $prop => $value) {
                $styles .= "$prop:$value;";
            }
            $attributes['style'] = $styles;
        }

        foreach ($attributes as $key => $value) {
            $element->setAttribute($key, $value);
        }
    }

    private function setChildren(\Dom\HTMLDocument $document, \Dom\HTMLElement $element, array $params): void
    {
        if ($this->config->hasChildren()) {
            for ($i = 0; $i < count($this->config->getChildren()); $i++) {
                $childConfig = $this->config->getChildren()[$i];
                $childElement = new Element(new ElementConfig($childConfig));
                $element->appendChild($childElement->render($document, $params));
            }
        }
    }

    private function setValue(\Dom\HTMLElement $element, array $params): void
    {
        if ($this->config->hasValue()) {
            $value = array_key_exists($this->config->getValue(), $params)
                ? $params[$this->config->getValue()]
                : $this->config->getValue();

            $element->textContent = $value;
        }
    }
}
