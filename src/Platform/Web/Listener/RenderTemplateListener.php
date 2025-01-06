<?php

declare(strict_types=1);

namespace MySchema\Platform\Web\Listener;

use MySchema\Action\ActionHandlerInterface;
use MySchema\Platform\Web\Action\HtmlRenderedAction;

class RenderTemplateListener implements ActionHandlerInterface
{
    public function getListeners(): array
    {
        return [
            HtmlRenderedAction::class => [$this, 'onHtmlRendered'],
        ];
    }

    public function onHtmlRendered(HtmlRenderedAction $action): void
    {
        $html = $action->getHtml();
        $dom = \Dom\HTMLDocument::createFromString($html);
        $head = $dom->head;
        if ($head instanceof \Dom\HTMLElement) {
            $meta = $dom->createElement('meta');
            $meta->setAttribute('name', 'generator');
            $meta->setAttribute('value', 'myschema');
            $head->appendChild($meta);
        }

        $action->setHtml($dom->saveHTML());
    }
}
