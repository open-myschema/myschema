<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Template\Engine\HtmlTemplate;

use Laminas\Escaper\Escaper;
use MySchema\Command\Psr7ResponseOutputInterface;
use MySchema\Platform\Web\Template\TemplateRendererInterface;
use MySchema\Resource\ResourceManager;
use Symfony\Component\Console\Output\OutputInterface;
use RuntimeException;

use function is_array;
use function is_string;

class HtmlTemplateRenderer implements TemplateRendererInterface
{
    private const string ATTRIBUTE_BIND_LANGUAGE = 'data-ms-bind-attr-lang';
    private const string ATTRIBUTE_BIND_HREF = 'data-ms-bind-attr-href';
    private const string ATTRIBUTE_BIND_VALUE = 'data-ms-bind-value';
    private const string ATTRIBUTE_TEMPLATE_BLOCK = 'data-ms-block';
    private string $template;

    public function __construct(private ResourceManager $resourceManager, private Escaper $escaper)
    {
    }

    public function render(OutputInterface $output): string
    {
        if (! isset($this->template)) {
            throw new RuntimeException(
                "Template not set"
            );
        }

        $params['site_title'] = 'MySchema';
        if ($output instanceof Psr7ResponseOutputInterface) {
            $params = match ($output->getDataType()) {
                "array" => $output->getData(),
                "int", "string", "null", "bool" => ['value' => $output->getData()],
                default => [],
            };
        }

        $template = $this->resourceManager->getTemplate($this->template);
        $document = \Dom\HTMLDocument::createFromString($template['contents']);
        foreach ($document->childNodes as $element) {
            if (! $element instanceof \Dom\HTMLElement) {
                continue;
            }
            $this->processElement($document, $element, $params);
        }

        return $document->saveHTML();
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    private function processAttributeBindLanguage(\Dom\HTMLElement $element, array $params): void
    {
        $element->removeAttribute(self::ATTRIBUTE_BIND_LANGUAGE);
        $element->setAttribute('lang', $params['lang'] ?? 'en');
    }

    private function processAttributeBindHref(\Dom\HTMLElement $element, array $params): void
    {
        $value = $element->getAttribute(self::ATTRIBUTE_BIND_HREF);
        $element->removeAttribute(self::ATTRIBUTE_BIND_HREF);
        if (isset($params[$value]) && \is_scalar($params[$value])) {
            $element->setAttribute('href', $params[$value]);
        }
    }

    private function processAttributeBindValue(\Dom\HTMLElement $element, array $params): void
    {
        $value = $element->getAttribute(self::ATTRIBUTE_BIND_VALUE);
        $element->removeAttribute(self::ATTRIBUTE_BIND_VALUE);
        if (isset($params[$value]) && \is_scalar($params[$value])) {
            $element->textContent = $this->escaper->escapeHtml((string) $params[$value]);
        }
    }

    private function processElement(\Dom\HTMLDocument $document, \Dom\HTMLElement $element, array $params): void
    {
        if ($element->hasAttribute(self::ATTRIBUTE_TEMPLATE_BLOCK)) {
            $this->processTemplateBlock($document, $element, $params);
        }

        if ($element->hasAttribute(self::ATTRIBUTE_BIND_LANGUAGE)) {
            $this->processAttributeBindLanguage($element, $params);
        }

        if ($element->hasAttribute(self::ATTRIBUTE_BIND_HREF)) {
            $this->processAttributeBindHref($element, $params);
        }

        if ($element->hasAttribute(self::ATTRIBUTE_BIND_VALUE)) {
            $this->processAttributeBindValue($element, $params);
        }

        // recursively process child nodes
        foreach ($element->childNodes as $node) {
            if (! $node instanceof \Dom\HTMLElement) {
                continue;
            }

            $this->processElement($document, $node, $params);
        }
    }

    private function processTemplateBlock(\Dom\HTMLDocument $document, \Dom\HTMLElement $element, array $params): void
    {
        $blockName = $element->getAttribute(self::ATTRIBUTE_TEMPLATE_BLOCK);
        $element->removeAttribute(self::ATTRIBUTE_TEMPLATE_BLOCK);

        // resolve, process the block
        $block = $this->resourceManager->getBlock($blockName);
        if (isset($block['config']['repeating']) && true === $block['config']['repeating']) {
            $param = $element->getAttribute('data-ms-bind-value');
            if (is_string($param) && isset($params[$param]) && is_array($params[$param])) {
                foreach ($params[$param] as $item) {
                    $itemElement = $document->createElement($element->tagName);
                    $itemElement->innerHTML = $block['contents'];
                    if (isset($block['config']['innerHTML']) && false === $block['config']['innerHTML']) {
                        $this->processElement($document, $itemElement->firstChild, $item);
                        $element->insertAdjacentElement(\Dom\AdjacentPosition::AfterEnd, $itemElement->firstChild);
                    } else {
                        $this->processElement($document, $itemElement, $item);
                        $element->insertAdjacentElement(\Dom\AdjacentPosition::AfterEnd, $itemElement);
                    }
                }
            }
            $element->remove();
        } else {
            $element->innerHTML = $block['contents'];
        }
    }
}
