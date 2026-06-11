<?php

namespace Vendor\HetznerCloud\Http\Middleware;

use GuzzleHttp\Exception\ConnectException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RetryMiddleware
{
    private int $maxRetries;

    private int $backoffMultiplier;

    public function __construct(int $maxRetries = 3, int $backoffMultiplier = 100)
    {
        $this->maxRetries = $maxRetries;
        $this->backoffMultiplier = $backoffMultiplier;
    }

    /**
     * Get the decider callback for Guzzle's retry middleware.
     */
    public function decider(): callable
    {
        return function (
            int $retries,
            RequestInterface $request,
            ?ResponseInterface $response = null,
            ?\Throwable $exception = null
        ): bool {
            if ($retries >= $this->maxRetries) {
                return false;
            }

            // Retry on connection exceptions (network issues)
            if ($exception instanceof ConnectException) {
                return true;
            }

            // Retry on server errors and rate limits
            if ($response !== null) {
                $status = $response->getStatusCode();
                if ($status === 429 || ($status >= 500 && $status <= 599)) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * Get the delay callback for Guzzle's retry middleware (returns delay in milliseconds).
     */
    public function delay(): callable
    {
        return function (int $retries, ?ResponseInterface $response = null): int {
            // Check for rate limit reset header if we got a 429
            if ($response !== null && $response->getStatusCode() === 429) {
                $resetHeader = $response->getHeaderLine('RateLimit-Reset');
                if ($resetHeader !== '') {
                    $resetTime = (int) $resetHeader;
                    $delaySeconds = max(1, $resetTime - time());

                    // Return delay in milliseconds
                    return $delaySeconds * 1000;
                }
            }

            // Exponential backoff
            return (int) (pow(2, $retries) * $this->backoffMultiplier);
        };
    }
}
