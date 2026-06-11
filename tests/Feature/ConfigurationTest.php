<?php

namespace Vendor\HetznerCloud\Tests\Feature;

use Vendor\HetznerCloud\Http\Client\HetznerClient;
use Vendor\HetznerCloud\Tests\TestCase;

class ConfigurationTest extends TestCase
{
    public function test_config_values_are_loaded()
    {
        $this->assertEquals('test-token', config('hetzner-cloud.token'));
        $this->assertEquals('https://api.hetzner.cloud/v1', config('hetzner-cloud.base_url'));
    }

    public function test_client_receives_config_values()
    {
        $this->app['config']->set('hetzner-cloud.token', 'custom-token');
        $this->app['config']->set('hetzner-cloud.timeout', 45);

        // Re-resolve client to apply changes
        $client = $this->app->make(HetznerClient::class);

        // Reflect token property or invoke request to see headers
        $reflection = new \ReflectionClass($client);
        $tokenProp = $reflection->getProperty('token');
        $tokenProp->setAccessible(true);
        $timeoutProp = $reflection->getProperty('timeout');
        $timeoutProp->setAccessible(true);

        $this->assertEquals('custom-token', $tokenProp->getValue($client));
        $this->assertEquals(45, $timeoutProp->getValue($client));
    }
}
