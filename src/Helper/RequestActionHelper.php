<?php

declare(strict_types=1);

namespace MySchema\Helper;

use Psr\Http\Message\ServerRequestInterface;

trait RequestActionHelper
{
    private ServerRequestInterface $request;

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
