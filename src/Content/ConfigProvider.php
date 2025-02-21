<?php

declare(strict_types=1);

namespace MySchema\Content;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'content' => $this->getContentConfig(),
            'resources' => $this->getResources(),
        ];
    }

    private function getContentConfig(): array
    {
        return [
            'types' => [
                'main::creative-work' => [
                    'name' => 'Creative Work',
                    'description' => 'The most generic kind of creative work, including books, movies, photographs, software programs, etc.',
                    'tag' => 'creative',
                ],
                'main::event' => [
                    'name' => 'Event',
                    'description' => 'An event happening at a certain time and location, such as a concert, lecture, or festival.',
                    'tag' => 'event',
                ],
                'main::organization' => [
                    'name' => 'Organization',
                    'description' => 'An organization such as a school, NGO, corporation, club, etc.',
                    'tag' => 'organization',
                ],
                'main::sports-organization' => [
                    'name' => 'Sports Organization',
                    'description' => '',
                    'parent' => 'main::organization',
                    'tag' => 'sports-organization',
                ],
                'main::person' => [
                    'name' => 'Person',
                    'description' => 'A person (alive, dead, or fictional).',
                    'tag' => 'persion',
                ],
                'main::place' => [
                    'name' => 'Place',
                    'description' => 'Entities that have a somewhat fixed, physical extension.',
                    'tag' => 'place',
                ],
                'main::sports-event' => [
                    'name' => 'Sports Event',
                    'description' => 'An event happening at a certain time and location, such as a concert, lecture, or festival.',
                    'parent' => 'main::event',
                    'tag' => 'sports-event',
                ],
            ],
        ];
    }

    private function getMigrations(): array
    {
        return [
            'main::create-content-tables' => [
                'description' => 'Create content tables',
                'up' => '/resources/migrations/content/up.sql',
                'down' => '/resources/migrations/content/down.sql',
            ],
        ];
    }

    private function getQueries(): array
    {
        return [
            'main::get-content-types' => [
                'file' => '/resources/queries/content/content_types.sql',
            ],
            'main::create-content' => [
                'file' => '/resources/queries/content/create_content.sql',
            ],
            'main::create-content-meta' => [
                'file' => '/resources/queries/content/create_content_meta.sql',
            ],
            'main::create-content-tag' => [
                'file' => '/resources/queries/content/create_content_tag.sql',
            ],
            'main::create-content-type' => [
                'file' => '/resources/queries/content/create_content_type.sql',
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
                'main::content-category-dashboard' => [
                    'file' => '/resources/templates/content/category_dashboard.html',
                ],
                'main::error-404' => [
                    'file' => '/resources/templates/error/404.html',
                ],
            ],
        ];
    }
}
