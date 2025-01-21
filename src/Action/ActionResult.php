<?php

declare(strict_types=1);

namespace MySchema\Action;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class ActionResult
{
    public function __construct(
        private mixed $data = null,
        private int $code = StatusCodeInterface::STATUS_OK,
        private array $headers = [],
        private array $messages = [],
        private string $template = ''
    ) {
    }

    public function getCode(): mixed
    {
        return $this->code;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getMessages(): array
    {
        return (array) $this->messages;
    }

    public function getDataType(): string
    {
        if ($this->data instanceof ResponseInterface) {
            return ResponseInterface::class;
        }

        $validReturns = ['array', 'int', 'bool', 'string', 'null'];
        if (! \in_array(\get_debug_type($this->data), $validReturns)) {
            return 'unknown';
        }

        return \get_debug_type($this->data);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function hasTemplate(): bool
    {
        return '' !== $this->template;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function setData(mixed $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function setMessage(string $message): static
    {
        $this->messages[] = $message;
        return $this;
    }

    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }
}
