<?php

declare(strict_types=1);

namespace MySchema\Content\Listener;

use MySchema\Content\Event\ContentCreatedEvent;
use MySchema\EventManager\EventListenerInterface;
use Psr\Container\ContainerInterface;

class ContentActionsListener implements EventListenerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListeners(): array
    {
        return [
            ContentCreatedEvent::class => ['listener' => [$this, 'onCreateContent']],
        ];
    }

    public function onCreateContent(ContentCreatedEvent $action): void
    {
        $content = $action->getContent();
    }
}
