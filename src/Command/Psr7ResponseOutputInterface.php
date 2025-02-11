<?php

declare(strict_types=1);

namespace MySchema\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

interface Psr7ResponseOutputInterface extends OutputInterface
{
    public function getData(): mixed;
    public function getDataType(): string;
    public function getError(): Throwable;
    public function getHeaders(): array;
    public function getStatusCode(): int;
    public function getTemplate(): string;
    public function hasData(): bool;
    public function hasError(): bool;
    public function hasTemplate(): bool;
    public function setData(mixed $data, string $dataType): void;
    public function setError(Throwable $error): void;
    public function setHeaders(array $headers): void;
    public function setStatusCode(int $statusCode): void;
    public function setTemplate(string $template): void;
}
