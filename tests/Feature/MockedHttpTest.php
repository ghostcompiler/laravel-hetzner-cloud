<?php

namespace Vendor\HetznerCloud\Tests\Feature;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Vendor\HetznerCloud\Exceptions\AuthenticationException;
use Vendor\HetznerCloud\Exceptions\NetworkException;
use Vendor\HetznerCloud\Exceptions\RateLimitException;
use Vendor\HetznerCloud\Exceptions\ValidationException;
use Vendor\HetznerCloud\Facades\Hetzner;
use Vendor\HetznerCloud\Http\Client\HetznerClient;
use Vendor\HetznerCloud\Http\Middleware\RetryMiddleware;
use Vendor\HetznerCloud\Tests\TestCase;

class MockedHttpTest extends TestCase
{
    private function createMockClient(array $responses, int $maxRetries = 3, int $retryBackoff = 0): HetznerClient
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);

        // Add retry middleware
        $retry = new RetryMiddleware($maxRetries, $retryBackoff);
        $stack->push(Middleware::retry($retry->decider(), $retry->delay()));

        $guzzle = new GuzzleClient([
            'handler' => $stack,
            'base_uri' => 'https://api.hetzner.cloud/v1/',
        ]);

        $client = new HetznerClient('mock-token', [
            'base_url' => 'https://api.hetzner.cloud/v1',
            'retries' => $maxRetries,
            'retry_backoff' => $retryBackoff,
        ]);
        $client->setGuzzleClient($guzzle);

        return $client;
    }

    public function test_get_servers_success()
    {
        $responseBody = json_encode([
            'servers' => [
                ['id' => 1, 'name' => 'web-1', 'status' => 'running'],
                ['id' => 2, 'name' => 'web-2', 'status' => 'off']
            ]
        ]);

        $client = $this->createMockClient([
            new Response(
                200,
                [
                    'RateLimit-Limit' => '3600',
                    'RateLimit-Remaining' => '3599',
                    'RateLimit-Reset' => '1718115600'
                ],
                $responseBody
            )
        ]);

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        $servers = $manager->servers()->all();

        $this->assertCount(2, $servers);
        $this->assertEquals('web-1', $servers->first()->name);

        $limits = $manager->rateLimit();
        $this->assertEquals(3600, $limits['limit']);
        $this->assertEquals(3599, $limits['remaining']);
        $this->assertEquals(1718115600, $limits['reset']);
    }

    public function test_auth_exception_mapping()
    {
        $client = $this->createMockClient([
            new Response(401, [], json_encode([
                'error' => [
                    'code' => 'unauthorized',
                    'message' => 'Invalid token'
                ]
            ]))
        ]);

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid token');

        $manager->servers()->all();
    }

    public function test_validation_exception_mapping()
    {
        $client = $this->createMockClient([
            new Response(422, [], json_encode([
                'error' => [
                    'code' => 'invalid_input',
                    'message' => 'Validation failed',
                    'details' => [
                        'fields' => [
                            ['name' => 'name', 'message' => ['must be unique']]
                        ]
                    ]
                ]
            ]))
        ]);

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        try {
            $manager->servers()->create(['name' => 'web-1']);
            $this->fail('Expected ValidationException was not thrown');
        } catch (ValidationException $e) {
            $this->assertEquals('Validation failed', $e->getMessage());
            $this->assertEquals('invalid_input', $e->getErrorCode());
            $this->assertEquals(['name' => ['must be unique']], $e->getErrors());
        }
    }

    public function test_network_exception_mapping()
    {
        $client = $this->createMockClient([
            new ConnectException('Connection timed out', new Request('GET', 'servers'))
        ], 0); // No retries

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Connection timed out');

        $manager->servers()->all();
    }

    public function test_rate_limit_exception_mapping_and_retries()
    {
        $responseBody = json_encode([
            'servers' => [['id' => 1, 'name' => 'web-1', 'status' => 'running']]
        ]);

        // First call: 429 Too Many Requests
        // Second call: 200 OK
        $client = $this->createMockClient([
            new Response(429, [
                'RateLimit-Limit' => '3600',
                'RateLimit-Remaining' => '0',
                'RateLimit-Reset' => (string)(time() + 1)
            ], json_encode([
                'error' => ['code' => 'rate_limit_exceeded', 'message' => 'Rate limit exceeded']
            ])),
            new Response(200, [], $responseBody)
        ], 3, 0); // 3 retries, 0ms backoff multiplier for test speed

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        // Should retry once and succeed
        $servers = $manager->servers()->all();

        $this->assertCount(1, $servers);
        $this->assertEquals('web-1', $servers->first()->name);
    }

    public function test_async_requests()
    {
        $responseBody = json_encode([
            'servers' => [['id' => 1, 'name' => 'async-web', 'status' => 'running']]
        ]);

        $client = $this->createMockClient([
            new Response(200, [], $responseBody)
        ]);

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        $promise = $manager->servers()->async()->all();

        $this->assertInstanceOf(\GuzzleHttp\Promise\PromiseInterface::class, $promise);

        // Wait for resolution
        $servers = $promise->wait();

        $this->assertInstanceOf(\Vendor\HetznerCloud\Collections\ServerCollection::class, $servers);
        $this->assertEquals('async-web', $servers->first()->name);
    }

    public function test_batch_operations()
    {
        $responseBody1 = json_encode(['server' => ['id' => 1, 'name' => 'web-1', 'status' => 'running']]);
        $responseBody2 = json_encode(['server' => ['id' => 2, 'name' => 'web-2', 'status' => 'running']]);

        $client = $this->createMockClient([
            new Response(200, [], $responseBody1),
            new Response(200, [], $responseBody2)
        ]);

        $manager = new \Vendor\HetznerCloud\Managers\HetznerManager($client);

        $results = $manager->batch([
            fn () => $manager->servers()->find(1),
            fn () => $manager->servers()->find(2)
        ]);

        $this->assertCount(2, $results);
        $this->assertInstanceOf(\Vendor\HetznerCloud\DTOs\Server::class, $results[0]);
        $this->assertInstanceOf(\Vendor\HetznerCloud\DTOs\Server::class, $results[1]);
        $this->assertEquals('web-1', $results[0]->name);
        $this->assertEquals('web-2', $results[1]->name);
    }
}
