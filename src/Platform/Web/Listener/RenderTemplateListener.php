<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Listener;

use MySchema\EventManager\EventListenerInterface;
use MySchema\Platform\Web\Event\HtmlRenderedEvent;
use Throwable;

class RenderTemplateListener implements EventListenerInterface
{
    public function getListeners(): array
    {
        return [
            HtmlRenderedEvent::class => [$this, 'onHtmlRendered'],
        ];
    }

    public function onHtmlRendered(HtmlRenderedEvent $action): void
    {
        $html = $action->getHtml();
        try {
            $dom = \Dom\HTMLDocument::createFromString($html);
            $head = $dom->head;
            if ($head instanceof \Dom\HTMLElement) {
                $meta = $dom->createElement('meta');
                $meta->setAttribute('name', 'generator');
                $meta->setAttribute('value', 'myschema');
                $head->appendChild($meta);
            }

            $action->setHtml($dom->saveHTML());
        } catch (Throwable) {}
    }
}
