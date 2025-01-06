<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

use MySchema\Action\ActionResult;
use MySchema\Platform\Web\DomTemplate\Resource\Block;
use MySchema\Platform\Web\DomTemplate\Resource\BlockConfig;
use MySchema\Platform\Web\TemplateRendererInterface;
use MySchema\Resource\ResourceManager;

class DomTemplateRenderer implements TemplateRendererInterface
{
    private string $template;

    public function __construct(private ResourceManager $resourceManager)
    {
    }

    public function render(ActionResult $result): string
    {
        $params = match ($result->getDataType()) {
            "array" => $result->getData(),
            "int", "string", "null", "bool" => ['value' => $result->getData()],
            default => [],
        };

        // build the template config
        $config = $this->getTemplateConfig();

        // create the document
        $page = \Dom\HTMLDocument::createEmpty();
        $html = $page->createElement('html');

        $head = $this->renderHead($page, $config);
        $body = $this->renderBody($page, $config, $params);

        $html->appendChild($head);
        $html->appendChild($body);     

        return "<!DOCTYPE html>" . $page->saveHTML($html);
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    private function getTemplateConfig(): array
    {
        if (! isset($this->template)) {
            throw new \InvalidArgumentException("Template not set");
        }

        $blocks = [];
        $definition = $this->resourceManager->getTemplate($this->template);
        foreach ($definition['blocks'] ?? [] as $blockName) {
            $blocks[$blockName] = $this->resourceManager->getBlock($blockName);
        }

        return [
            'title' => $definition['title'] ?? '',
            'blocks' => $blocks,
        ];
    }

    private function renderBody(\Dom\HTMLDocument $document, array $templateConfig, array $params): \Dom\HTMLElement
    {
        $body = $document->createElement('body');

        // render blocks
        foreach ($templateConfig['blocks'] as $blockDefinition) {
            $elements = [];
            foreach ($blockDefinition['elements'] ?? [] as $element) {
                if (isset($element['tag']) && $element['tag'] === 'form') {
                    $formName = $element['value'];
                    unset($element['value']);
                    $formDefinition = $this->resourceManager->getForm($formName);
                    $element['attributes'] = $formDefinition['attributes'] ?? [];
                    $element['children'] = $formDefinition['elements'];
                }
                $elements[] = $element;
            }
            $blockDefinition['elements'] = $elements;
            $block = new Block(new BlockConfig($blockDefinition));
            $body->appendChild($block->render($document, $params));
        }

        return $body;
    }

    private function renderHead(\Dom\HTMLDocument $document, array $templateConfig): \Dom\HTMLElement
    {
        $head = $document->createElement('head');

        // meta tags
        $meta = [
            'charset' => $document->charset,
            'viewport' => 'width=device-width, initial-scale=1',
        ];
        foreach (\array_merge($meta, $templateConfig['meta'] ?? []) as $key => $value) {
            $el = $document->createElement('meta');
            $el->setAttribute($key, $value);
            $head->appendChild($el);
        }

        // title
        $title = $document->createElement('title');
        $title->textContent = $templateConfig['title'];
        $head->appendChild($title);

        return $head;
    }
}
