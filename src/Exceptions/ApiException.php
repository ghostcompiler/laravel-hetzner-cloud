<?php

namespace Vendor\HetznerCloud\Exceptions;

class ApiException extends HetznerException
{
    protected string $errorCode;
    protected array $details;

    public function __construct(string $message, int $code, string $errorCode = '', array $details = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->details = $details;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorDetails(): array
    {
        return $this->details;
    }
}
