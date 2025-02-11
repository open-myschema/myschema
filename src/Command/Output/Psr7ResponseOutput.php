<?php

declare(strict_types=1);

namespace MySchema\Command\Output;

use MySchema\Command\Psr7ResponseOutputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

final class Psr7ResponseOutput extends BufferedOutput implements Psr7ResponseOutputInterface
{
    private mixed $data;
    private string $dataType;
    private Throwable $error;
    private array $headers = [];
    private int $statusCode = 200;
    private string $template;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getError(): Throwable
    {
        return $this->error;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function hasData(): bool
    {
        return isset($this->data);
    }

    public function hasError(): bool
    {
        return isset($this->error);
    }

    public function hasTemplate(): bool
    {
        return isset($this->template);
    }

    public function setData(mixed $data, string $dataType): void
    {
        $this->data = $data;
        $this->dataType = $dataType;
    }

    public function setError(Throwable $error): void
    {
        $this->error = $error;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
}
