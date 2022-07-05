<?php

namespace Dmpty\LaravelUtilities\Error\Exceptions;

use Exception;
use Throwable;

class BaseException extends Exception implements ExceptionInterface
{
    protected bool $reportable = true;

    protected array $data;

    protected int $status = 500;

    public function __construct($message = "", $data = [], $code = 0, Throwable $previous = null)
    {
        $raw = $data;
        if ($data && is_string($data)) {
            $data = json_decode($data, 1);
            if (!is_array($data)) {
                $data = [
                    'content' => $raw
                ];
            }
        }
        $this->data = is_array($data) ? $data : [];
        parent::__construct($message, $code, $previous);
    }

    public function isReportable(): bool
    {
        return $this->reportable;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setStatus($status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
