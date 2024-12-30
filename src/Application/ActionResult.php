<?php

declare(strict_types=1);

namespace MySchema\Application;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

class ActionResult
{
    public function __construct(
        private mixed $data = null,
        private int $code = StatusCodeInterface::STATUS_OK,
        private string $message = ""
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

    public function getMessage(): mixed
    {
        return $this->message;
    }

    public function getDataType(): string
    {
        if ($this->data instanceof ResponseInterface) {
            return ResponseInterface::class;
        }

        $validReturns = ['int', 'bool', 'string', 'null'];
        if (! \in_array(\get_debug_type($this->data), $validReturns)) {
            return 'unknown';
        }

        return \get_debug_type($this->data);
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

    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }
}
