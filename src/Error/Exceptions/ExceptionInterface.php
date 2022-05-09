<?php

namespace Dmpty\LaravelUtilities\Error\Exceptions;

interface ExceptionInterface
{
    public function isReportable(): bool;

    public function getData(): array;

    public function setStatus($status): static;

    public function getStatus(): int;
}
