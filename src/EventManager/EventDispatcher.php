<?php

declare(strict_types=1);

namespace MySchema\EventManager;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private ListenerProviderInterface $listenerProvider)
    {
    }

    public function dispatch(object $event): object
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);
        foreach ($listeners as $listener) {
            $listener($event);
            if ($event instanceof StoppableEventInterface) {
                if ($event->isPropagationStopped()) {
                    break;
                }
            }
        }

        return $event;
    }
}
