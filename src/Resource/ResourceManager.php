<?php

declare(strict_types=1);

namespace MySchema\Resource;

use League\Flysystem\Filesystem;
use InvalidArgumentException;
use RuntimeException;

use function array_key_exists;
use function get_debug_type;
use function is_array;
use function sprintf;
use function strpos;

class ResourceManager
{
    public function __construct(private Filesystem $filesystem, private array $resourcesConfig)
    {
    }

    public function getAllMigrations(): array
    {
        return $this->resourcesConfig['migrations'] ?? [];
    }

    public function getBlock(string $blockName): array
    {
        $config = $this->resourcesConfig['blocks'] ?? [];
        if (! array_key_exists($blockName, $config)) {
            throw new InvalidArgumentException(sprintf(
                "Block %s not found in configuration",
                $blockName
            ));
        }

        if (! is_array($config[$blockName])) {
            throw new InvalidArgumentException(sprintf(
                "Expected block %s config to be an array, %s given instead",
                $blockName,
                get_debug_type($config[$blockName])
            ));
        }

        if (! isset($config[$blockName]['file'])) {
            throw new InvalidArgumentException(sprintf(
                "Invalid block %s config. `file` key not found",
                $blockName
            ));
        }

        $block = $this->filesystem->read($config[$blockName]['file']);
        if (! $block) {
            throw new InvalidArgumentException(sprintf(
                "Block %s, file %s not found",
                $blockName,
                $config[$blockName]['file']
            ));
        }

        $parser = $this->getParser($config[$blockName]['file']);
        if (! $parser) {
            throw new RuntimeException(sprintf(
                "Parser for block %s not found",
                $blockName
            ));
        }

        return [
            'config' => $config[$blockName],
            'contents' => $parser->parseResource($block),
        ];
    }

    public function getMigration(string $connectionDriver, string $migrationName, string $direction): string
    {
        $config = $this->resourcesConfig['migrations'] ?? [];
        if (! array_key_exists($migrationName, $config)) {
            throw new InvalidArgumentException(sprintf(
                "Migration %s not found in configuration",
                $migrationName
            ));
        }

        if (! isset($config[$migrationName][$connectionDriver])) {
            throw new InvalidArgumentException(sprintf(
                "Migration %s for connection driver %s not found in configuration",
                $migrationName,
                $connectionDriver
            ));
        }

        if (! isset($config[$migrationName][$connectionDriver][$direction])) {
            throw new InvalidArgumentException(sprintf(
                "%s migration %s for connection driver %s not found in configuration",
                $direction,
                $migrationName,
                $connectionDriver
            ));
        }

        return $this->filesystem->read($config[$migrationName][$connectionDriver][$direction]);
    }

    public function getQuery(string $connectionDriver, string $queryName): string
    {
        $config = $this->resourcesConfig['queries'] ?? [];
        if (! array_key_exists($queryName, $config)) {
            throw new InvalidArgumentException(sprintf(
                "Query %s not found in configuration",
                $queryName
            ));
        }

        if (! is_array($config[$queryName])) {
            throw new InvalidArgumentException(sprintf(
                "Expected query %s config to be an array, %s given instead",
                $queryName,
                get_debug_type($config[$queryName])
            ));
        }

        if (! isset($config[$queryName][$connectionDriver])) {
            throw new InvalidArgumentException(sprintf(
                "Query %s for connection driver %s not found in configuration",
                $queryName,
                $connectionDriver
            ));
        }

        if (! isset($config[$queryName][$connectionDriver]['file'])) {
            throw new InvalidArgumentException(sprintf(
                "Query %s for connection driver %s has no configured file key",
                $queryName,
                $connectionDriver
            ));
        }

        return $this->filesystem->read($config[$queryName][$connectionDriver]['file']);
    }

    public function getTemplate(string $templateName): array|bool|string
    {
        $config = $this->resourcesConfig['templates'] ?? [];
        if (! array_key_exists($templateName, $config)) {
            throw new InvalidArgumentException(sprintf(
                "Template %s not found in configuration",
                $templateName
            ));
        }

        if (! is_array($config[$templateName])) {
            throw new InvalidArgumentException(sprintf(
                "Expected template %s config to be an array, %s given instead",
                $templateName,
                get_debug_type($config[$templateName])
            ));
        }

        if (! isset($config[$templateName]['file'])) {
            throw new InvalidArgumentException(sprintf(
                "Invalid template %s config. `file` key not found",
                $templateName
            ));
        }

        $template = $this->filesystem->read($config[$templateName]['file']);
        if (! $template) {
            throw new RuntimeException(sprintf(
                "Template %s, file %s not found",
                $templateName,
                $config[$templateName]
            ));
        }

        $parser = $this->getParser($config[$templateName]['file']);
        if (! $parser) {
            throw new RuntimeException(sprintf(
                "Parser for template %s not found",
                $templateName
            ));
        }

        return [
            'config' => $config[$templateName],
            'contents' => $parser->parseResource($template)
        ];
    }

    private function getParser(string $resourceName): ResourceParserInterface|bool
    {
        if (false !== strpos($resourceName, '.json')) {
            return new JsonResourceParser;
        }

        if (false !== strpos($resourceName, '.html') || false !== strpos($resourceName, '.twig')) {
            return new HtmlResourceParser;
        }

        return false;
    }
}
