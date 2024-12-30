<?php

declare(strict_types=1);

namespace MySchema\Page;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'console' => $this->getConsoleCommands(),
            'dependencies' => $this->getDependencies(),
            'schema' => $this->getSchemaConfig(),
        ];
    }

    private function getConsoleCommands(): array
    {
        return [
            'commands' => [
                Command\CreatePageCommand::class,
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Command\CreatePageCommand::class => \MySchema\Application\ConsoleCommandFactory::class,
            ],
        ];
    }

    private function getSchemaConfig(): array
    {
        return [
            'main' => [
                'page' => [
                    'columns' => [
                        'id' => [
                            'type' => 'bigint',
                            'unsigned' => TRUE,
                            'autoIncrement' => TRUE,
                        ],
                        'title' => [
                            'type' => 'string',
                        ],
                        'description' => [
                            'type' => 'string',
                        ],
                        'url' => [
                            'type' => 'text',
                        ],
                    ],
                    'indexes' => [
                        'page_url' => [
                            'column' => ['url'],
                        ],
                    ],
                ],
                'page_meta' => [
                    'columns' => [
                        'id' => [
                            'type' => 'bigint',
                            'unsigned' => TRUE,
                            'autoIncrement' => TRUE,
                        ],
                        'page_id' => [
                            'type' => 'bigint',
                        ],
                        'status' => [
                            'type' => 'smallint',
                        ],
                        'description' => [
                            'type' => 'string',
                        ],
                        'data' => [
                            'type' => 'json',
                            'notnull' => FALSE,
                        ],
                        'timestamp' => [
                            'type' => 'datetimetz',
                        ],
                    ],
                    'indexes' => [
                        'content_meta_status' => [
                            'column' => ['status'],
                        ],
                        'content_meta_timestamp' => [
                            'column' => ['timestamp'],
                        ],
                    ],
                    'constraints' => [
                        'foreign_keys' => [
                            'page_meta_page_id_fk' => [
                                'column' => 'page_id',
                                'references' => [
                                    'model' => 'page',
                                    'column' => 'id'
                                ],
                                'options' => [
                                    'onUpdate' => 'CASCADE',
                                    'onDelete' => 'CASCADE',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
