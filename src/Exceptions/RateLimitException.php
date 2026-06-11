<?php

namespace Vendor\HetznerCloud\Exceptions;

class RateLimitException extends ApiException
{
    protected ?int $limit = null;

    protected ?int $remaining = null;

    protected ?int $reset = null;

    public function __construct(
        string $message,
        int $code,
        string $errorCode = '',
        array $details = [],
        ?int $limit = null,
        ?int $remaining = null,
        ?int $reset = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $errorCode, $details, $previous);
        $this->limit = $limit;
        $this->remaining = $remaining;
        $this->reset = $reset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getRemaining(): ?int
    {
        return $this->remaining;
    }

    public function getResetTimestamp(): ?int
    {
        return $this->reset;
    }

    public function getSecondsUntilReset(): int
    {
        if ($this->reset === null) {
            return 60; // default backup delay
        }

        return max(0, $this->reset - time());
    }
}
