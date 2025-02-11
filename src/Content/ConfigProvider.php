<?php

declare(strict_types=1);

namespace MySchema\Content;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'content' => $this->getContentConfig(),
            'dependencies' => $this->getDependencies(),
            'resources' => $this->getResources(),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getContentConfig(): array
    {
        return [
            'types' => [
                'main::creative-work' => [
                    'name' => 'Creative Work',
                    'description' => 'The most generic kind of creative work, including books, movies, photographs, software programs, etc.',
                ],
                'main::event' => [
                    'name' => 'Event',
                    'description' => 'An event happening at a certain time and location, such as a concert, lecture, or festival.',
                ],
                'main::organization' => [
                    'name' => 'Organization',
                    'description' => 'An organization such as a school, NGO, corporation, club, etc.',
                ],
                'main::sports-organization' => [
                    'name' => 'Sports Organization',
                    'description' => '',
                    'parent' => 'main::organization',
                ],
                'main::person' => [
                    'name' => 'Person',
                    'description' => 'A person (alive, dead, or fictional).',
                ],
                'main::place' => [
                    'name' => 'Place',
                    'description' => 'Entities that have a somewhat fixed, physical extension.',
                ],
                'main::sports-event' => [
                    'name' => 'Sports Event',
                    'description' => 'An event happening at a certain time and location, such as a concert, lecture, or festival.',
                    'parent' => 'main::event',
                ],
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [];
    }

    private function getMigrations(): array
    {
        return [
            'main::create-content-tables' => [
                'description' => 'Create content tables',
                'postgres' => [
                    'up' => '/resources/migrations/postgres/content/up.sql',
                    'down' => '/resources/migrations/postgres/content/down.sql',
                ],
            ],
        ];
    }

    private function getQueries(): array
    {
        return [
            'main::content-types' => [
                'postgres' => [
                    'file' => '/resources/queries/postgres/content/content_types.sql',
                ],
            ],
            'main::create-content' => [
                'postgres' => [
                    'file' => '/resources/queries/postgres/content/create_content.sql',
                ],
            ],
            'main::create-content-meta' => [
                'postgres' => [
                    'file' => '/resources/queries/postgres/content/create_content_meta.sql',
                ],
            ],
            'main::create-content-tag' => [
                'postgres' => [
                    'file' => '/resources/queries/postgres/content/create_content_tag.sql',
                ],
            ],
            'main::create-content-type' => [
                'postgres' => [
                    'file' => '/resources/queries/postgres/content/create_content_type.sql',
                ],
            ],
        ];
    }

    private function getResources(): array
    {
        return [
            'blocks' => [
                'main::top-navbar' => [
                    'file' => '/resources/blocks/navigation/navbar.html',
                ],
            ],
            'migrations' => $this->getMigrations(),
            'queries' => $this->getQueries(),
            'templates' => [
                'main::content-dashboard' => [
                    'file' => '/resources/templates/content/dashboard.html',
                ],
                'main::content-category-dashboard' => [
                    'file' => '/resources/templates/content/category_dashboard.html',
                ],
                'main::error-404' => [
                    'file' => '/resources/templates/error/404.html',
                ],
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            '/t/{category}' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::content-category-dashboard',
                'options' => [
                    'template' => 'main::content-category-dashboard',
                ],
            ],
            '/c/{identifier}' => [
                'methods' => ['GET', 'POST'],
                'name' => 'main::content',
                'options' => [
                    'template' => 'main::content-category-dashboard',
                ],
            ],
        ];
    }
}
