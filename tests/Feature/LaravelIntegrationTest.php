<?php

namespace Vendor\HetznerCloud\Tests\Feature;

use Vendor\HetznerCloud\Facades\Hetzner;
use Vendor\HetznerCloud\Http\Client\HetznerClient;
use Vendor\HetznerCloud\Managers\HetznerManager;
use Vendor\HetznerCloud\Managers\ServerManager;
use Vendor\HetznerCloud\Managers\VolumeManager;
use Vendor\HetznerCloud\Tests\TestCase;

class LaravelIntegrationTest extends TestCase
{
    public function test_facade_resolves_to_manager()
    {
        $manager = Hetzner::getFacadeRoot();
        $this->assertInstanceOf(HetznerManager::class, $manager);
    }

    public function test_container_resolves_singleton_manager()
    {
        $manager1 = $this->app->make(HetznerManager::class);
        $manager2 = $this->app->make('hetzner');

        $this->assertSame($manager1, $manager2);
    }

    public function test_container_resolves_singleton_client()
    {
        $client = $this->app->make(HetznerClient::class);
        $manager = $this->app->make(HetznerManager::class);

        $this->assertSame($client, $manager->client());
    }

    public function test_facade_submanagers()
    {
        $this->assertInstanceOf(ServerManager::class, Hetzner::servers());
        $this->assertInstanceOf(VolumeManager::class, Hetzner::volumes());
    }
}
