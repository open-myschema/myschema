<?php

declare(strict_types= 1);

namespace MySchema\Platform\Web\DomTemplate;

use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use MySchema\Application\ActionResult;
use MySchema\Platform\Web\DomTemplate\Resource\Block;
use MySchema\Platform\Web\DomTemplate\Resource\BlockConfig;
use MySchema\Platform\Web\TemplateRendererInterface;

class DomTemplateRenderer implements TemplateRendererInterface
{
    private string $template;

    public function __construct(private Filesystem $filesystem)
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

        $split = \explode('::', $this->template);
        if (\count($split) !== 2) {
            throw new \InvalidArgumentException(\sprintf(
                "Invalid template name %s",
                $this->template
            ));
        }

        $namespace = $split[0];
        $template = $split[1];

        $blocks = [];
        $definition = $this->getResource($namespace, $template);
        foreach ($definition['blocks'] ?? [] as $blockName) {
            $blocks[$blockName] = $this->getResource($namespace, $blockName);
        }

        return [
            'title' => $definition['title'] ?? '',
            'blocks' => $blocks,
        ];
    }

    private function getResource(string $directory, string $resourceName): array
    {
        if (false !== \strpos($resourceName, '/')) {
            $nameSplit = \explode('/', $resourceName);
            if (\count($nameSplit) > 1) {
                if (false !== \strpos($nameSplit[0], '::')) {
                    $subSplit = \explode('::', $nameSplit[0]);
                    $directory = $directory . DIRECTORY_SEPARATOR . $subSplit[1];
                } else {
                    $directory = $directory . DIRECTORY_SEPARATOR . $nameSplit[0];
                }

                return $this->getResource($directory, \implode('/', \array_slice($nameSplit, 1)));
            }
        }

        foreach ($this->filesystem->listContents($directory) as $item) {
            if ($item instanceof FileAttributes) {
                $pathSplit = \explode('/', $item->path());
                if ($resourceName === $pathSplit[\count($pathSplit) - 1]) {
                    return $this->parseFile($this->filesystem->read($item->path()), $resourceName);
                }
            }
        }

        throw new \InvalidArgumentException(\sprintf(
            "Resource %s in directory %s not found",
            $resourceName,
            $directory
        ));
    }

    private function parseFile(string $contents, string $resourceName): array
    {
        if (false !== \strpos($resourceName, '.json')) {
            $parser = new DomJsonTemplateParser();
            return $parser->parse($contents);
        }

        throw new \InvalidArgumentException(sprintf(
            "Could not parse file %s. Appropriate %s implementation not found",
            $resourceName,
            DomTemplateParserInterface::class
        ));
    }

    private function renderBody(\Dom\HTMLDocument $document, array $templateConfig, array $params): \Dom\HTMLElement
    {
        $body = $document->createElement('body');

        // render blocks
        foreach ($templateConfig['blocks'] as $blockDefinition) {
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
