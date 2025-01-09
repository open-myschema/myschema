<?php

declare(strict_types=1);

namespace MySchema\Content\Listener;

use MySchema\Action\ActionHandlerInterface;
use MySchema\Content\Action\ContentCreatedAction;
use Psr\Container\ContainerInterface;

class ContentActionsListener implements ActionHandlerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getListeners(): array
    {
        return [
            ContentCreatedAction::class => ['listener' => [$this, 'onCreateContent']],
        ];
    }

    public function onCreateContent(ContentCreatedAction $action): void
    {
        $content = $action->getContent();
    }
}
