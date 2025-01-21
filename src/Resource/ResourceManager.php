<?php

declare(strict_types=1);

namespace MySchema\Resource;

use League\Flysystem\Filesystem;
use function array_key_exists;
use function strpos;

class ResourceManager
{
    public function __construct(private Filesystem $filesystem, private array $resourcesConfig)
    {
    }

    public function getBlock(string $blockName): array|bool|string
    {
        $config = $this->resourcesConfig['blocks'] ?? [];
        $block = $this->getResource($blockName, $config);
        if (! $block) return false;

        $parser = $this->getParser($config[$blockName]);
        if (! $parser) return false;

        return $parser->parseResource($block);
    }

    public function getForm(string $formName): array|bool
    {
        $config = $this->resourcesConfig['forms'] ?? [];
        $block = $this->getResource($formName, $config);
        if (! $block) return false;

        $parser = $this->getParser($config[$formName]);
        if (! $parser) return false;

        return $parser->parseResource($block);
    }

    public function getQuery(string $connectionDriver, string $queryName): string|bool
    {
        $config = $this->resourcesConfig['queries'] ?? [];
        return $this->getResource($queryName, $config);
    }

    public function getTemplate(string $templateName): array|bool|string
    {
        $config = $this->resourcesConfig['templates'] ?? [];
        $template = $this->getResource($templateName, $config);
        if (! $template) return false;

        $parser = $this->getParser($config[$templateName]);
        if (! $parser) return false;

        return $parser->parseResource($template);
    }

    private function getResource(string $resourceName, array $config): string|bool
    {
        if (! array_key_exists($resourceName, $config)) return false;
        return $this->filesystem->read($config[$resourceName]);
    }

    private function getParser(string $resourceName): ResourceParserInterface|bool
    {
        if (false !== strpos($resourceName, '.json')) {
            return new JsonResourceParser;
        }

        if (false !== strpos($resourceName, '.html')) {
            return new HtmlResourceParser;
        }

        return false;
    }
}
