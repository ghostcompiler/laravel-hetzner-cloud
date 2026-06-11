<?php

namespace Vendor\HetznerCloud\Tests\Unit;

use Vendor\HetznerCloud\Exceptions\ApiException;
use Vendor\HetznerCloud\Exceptions\RateLimitException;
use Vendor\HetznerCloud\Exceptions\ValidationException;
use Vendor\HetznerCloud\Tests\TestCase;

class ExceptionTest extends TestCase
{
    public function test_api_exception_properties()
    {
        $e = new ApiException('Error message', 400, 'bad_request', ['some' => 'detail']);

        $this->assertEquals('Error message', $e->getMessage());
        $this->assertEquals(400, $e->getCode());
        $this->assertEquals('bad_request', $e->getErrorCode());
        $this->assertEquals(['some' => 'detail'], $e->getErrorDetails());
    }

    public function test_validation_exception_parsing()
    {
        $details = [
            'fields' => [
                [
                    'name' => 'server_type',
                    'message' => ['is required', 'must be valid'],
                ],
                [
                    'name' => 'name',
                    'message' => ['must be unique'],
                ],
            ],
        ];

        $e = new ValidationException('Unprocessable Entity', 422, 'invalid_input', $details);

        $errors = $e->getErrors();

        $this->assertCount(2, $errors);
        $this->assertEquals(['is required', 'must be valid'], $errors['server_type']);
        $this->assertEquals(['must be unique'], $errors['name']);
    }

    public function test_rate_limit_exception_properties()
    {
        $e = new RateLimitException(
            'Too many requests',
            429,
            'rate_limit_exceeded',
            [],
            3600,
            0,
            time() + 60
        );

        $this->assertEquals(3600, $e->getLimit());
        $this->assertEquals(0, $e->getRemaining());
        $this->assertGreaterThan(time(), $e->getResetTimestamp());
        $this->assertGreaterThanOrEqual(58, $e->getSecondsUntilReset());
    }
}
