<?php

declare(strict_types=1);

namespace MySchema\Action;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class LazyActionListener implements ListenerProviderInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListenersForEvent(object $event): array|\Traversable
    {
        $listeners = [];
        $config = $this->container->get('config')['actions'];
        foreach ($config ?? [] as $listener => $actions) {
            if (! \in_array(\get_class($event), $actions, TRUE)) {
                continue;
            }

            $listenerInstance = new $listener($this->container);
            if (! $listenerInstance instanceof ActionHandlerInterface) {
                continue;
            }

            $listenerActions = $listenerInstance->getListeners();
            if (! isset($listenerActions[\get_class($event)])) {
                continue;
            }

            $listeners[] = $listenerActions[\get_class($event)];
        }

        return $listeners;
    }
}
