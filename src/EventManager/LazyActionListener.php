<?php

declare(strict_types=1);

namespace MySchema\EventManager;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Throwable;

use function get_class;
use function in_array;

final class LazyActionListener implements ListenerProviderInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListenersForEvent(object $event): array|\Traversable
    {
        $listeners = [];
        $config = $this->container->get('config')['actions'];
        $eventName = get_class($event);
        foreach ($config ?? [] as $listener => $actions) {
            if (! in_array($eventName, $actions, TRUE)) {
                continue;
            }

            try {
                $listenerInstance = new $listener($this->container);
                if (! $listenerInstance instanceof EventListenerInterface) {
                    continue;
                }
            } catch (Throwable) {
                continue;
            }

            $listenerActions = $listenerInstance->getListeners();
            if (! isset($listenerActions[$eventName])) {
                continue;
            }

            // @todo consider priority

            $listeners[] = $listenerActions[$eventName];
        }

        return $listeners;
    }
}
