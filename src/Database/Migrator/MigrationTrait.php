<?php

declare(strict_types= 1);

namespace MySchema\Database\Migrator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaDiff;
use MySchema\Database\ConnectionFactory;

trait MigrationTrait
{
    private function buildSchema(array $definitions): Schema
    {
        $schema = new Schema();
        foreach ($definitions as $name => $modelDefinition) {
            $table = $schema->createTable($name);
            foreach ($modelDefinition['columns'] ?? [] as $columnName => $columnDefinition) {
                $type = $columnDefinition['type'];
                unset($columnDefinition['type']);
                $table->addColumn($columnName, $type, $columnDefinition);
            }

            foreach ($modelDefinition['indexes'] ?? [] as $indexName => $indexDefinition) {
                if ('primary' === $indexName) {
                    $table->setPrimaryKey($indexDefinition);
                    continue;
                }
                $flags = $indexDefinition['flags'] ?? [];
                $options = $indexDefinition['options'] ?? [];
                $column = (array) $indexDefinition['column'];
                $table->addIndex($column, $indexName, $flags, $options);
            }
    
            foreach ($modelDefinition['constraints']['unique'] ?? [] as $constraint => $constraintDefinition) {
                $columns = (array) $constraintDefinition['columns'];
                $name = $constraintDefinition['name'];
                $flags = $constraintDefinition['flags'] ?? [];
                $options = $constraintDefinition['options'] ?? [];
                $table->addUniqueConstraint($columns, $name, $flags, $options);
            }

            foreach ($modelDefinition['constraints']['foreign_keys'] ?? [] as $constraint => $constraintDefinition) {
                $foreignTable = $constraintDefinition['references']['model'];
                $localColumns = (array) $constraintDefinition['column'];
                $foreignColumns = (array) $constraintDefinition['references']['column'];
                $options = $constraintDefinition['options'] ?? [];
                $table->addForeignKeyConstraint($foreignTable, $localColumns, $foreignColumns, $options, $constraint);
            }
        }
        return $schema;
    }

    private function getDatabaseConnection(string $connectionName = 'main'): Connection
    {
        return (new ConnectionFactory($this->container))->connect($connectionName);
    }

    private function generateMigrations(): array
    {
        $migrations = [];
        $schema = $this->container->get('config')['schema'];

        foreach ($schema as $database => $definitions) {
            if (empty($definitions)) {
                continue;
            }

            $schemaManager = $this->getDatabaseConnection($database)->createSchemaManager();
            $fromSchema = $schemaManager->introspectSchema();
            $toSchema = $this->buildSchema($definitions);
            $diff = $schemaManager->createComparator()->compareSchemas($fromSchema, $toSchema);
            if ($diff->isEmpty()) {
                continue;
            }
            $migrations[$database] = $diff;
        }
        return $migrations;
    }

    private function getMigrationSql(array $migrations): array
    {
        $created = [];
        $altered = [];
        $dropped = [];
        foreach ($migrations as $database => $schemaDiff) {
            if (! $schemaDiff instanceof SchemaDiff) {
                continue;
            }

            $connection = $this->getDatabaseConnection($database);
            $platform = $connection->getDatabasePlatform();
            foreach ($schemaDiff->getCreatedTables() as $table) {
                $schema = new Schema([$table]);
                $created[$database][] = $schema->toSql($connection->getDatabasePlatform());
            }

            foreach ($schemaDiff->getAlteredTables() as $table) {
                // @todo revisit this
                $altered[$database][] = $platform->getAlterTableSQL($table);
            }

            foreach ($schemaDiff->getDroppedTables() as $table) {
                // @todo implement
            }
        }

        // @todo cleanup
        $result = [];
        foreach ($created as $database => $items) {
            foreach ($items as $statements) {
                foreach ($statements as $statement) {
                    $result[$database][] = $statement;
                }
            }
        }
        foreach ($altered as $database => $items) {
            foreach ($items as $statements) {
                foreach ($statements as $statement) {
                    $result[$database][] = $statement;
                }
            }
        }
        foreach ($dropped as $database => $items) {
            foreach ($items as $statements) {
                foreach ($statements as $statement) {
                    $result[$database][] = $statement;
                }
            }
        }

        return $result;
    }

    private function setupMigrations(): bool
    {
        // build schema
        $definitions = $this->container->get('config')['schema'];
        $schema = $this->buildSchema(['migration' => $definitions['main']['migration']]);

        // execute statements
        $connection = $this->getDatabaseConnection();
        foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
            $connection->executeStatement($sql);
        }

        // check table existence
        if (! $connection->createSchemaManager()->tableExists('migration')) {
            return FALSE;
        }

        return TRUE;
    }
}
