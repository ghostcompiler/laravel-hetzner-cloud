<?php

namespace Vendor\HetznerCloud\Managers;

use GuzzleHttp\Promise\Utils;
use Vendor\HetznerCloud\Http\Client\HetznerClient;

class HetznerManager
{
    private HetznerClient $client;

    private array $managers = [];

    public function __construct(HetznerClient $client)
    {
        $this->client = $client;
    }

    public function authenticate(string $token): self
    {
        $this->client->authenticate($token);

        return $this;
    }

    public function client(): HetznerClient
    {
        return $this->client;
    }

    public function version(): string
    {
        $path = __DIR__.'/../../composer.json';
        if (file_exists($path)) {
            $composer = json_decode(file_get_contents($path), true);

            return $composer['version'] ?? '1.0.0';
        }

        return '1.0.0';
    }

    public function config(): array
    {
        if (function_exists('config')) {
            return config('hetzner-cloud') ?: [];
        }

        return [];
    }

    public function rateLimit(): array
    {
        return $this->client->getLastRateLimit();
    }

    public function ping(): bool
    {
        try {
            $this->locations()->perPage(1)->get();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function health(): array
    {
        $start = microtime(true);
        $ping = $this->ping();
        $latency = (int) ((microtime(true) - $start) * 1000);

        return [
            'status' => $ping ? 'healthy' : 'unhealthy',
            'latency_ms' => $latency,
            'timestamp' => time(),
        ];
    }

    /**
     * Run multiple SDK requests concurrently.
     */
    public function batch(array $callbacks): array
    {
        $this->client->startBatch();

        $callbackPromises = [];
        foreach ($callbacks as $callback) {
            $callbackPromises[] = $callback();
        }

        $this->client->endBatch();

        if (empty($callbackPromises)) {
            return [];
        }

        // Wait for all callback promises concurrently
        return Utils::all($callbackPromises)->wait();
    }

    public function servers(): ServerManager
    {
        return $this->getManager(ServerManager::class);
    }

    public function volumes(): VolumeManager
    {
        return $this->getManager(VolumeManager::class);
    }

    public function networks(): NetworkManager
    {
        return $this->getManager(NetworkManager::class);
    }

    public function firewalls(): FirewallManager
    {
        return $this->getManager(FirewallManager::class);
    }

    public function floatingIps(): FloatingIpManager
    {
        return $this->getManager(FloatingIpManager::class);
    }

    public function primaryIps(): PrimaryIpManager
    {
        return $this->getManager(PrimaryIpManager::class);
    }

    public function loadBalancers(): LoadBalancerManager
    {
        return $this->getManager(LoadBalancerManager::class);
    }

    public function sshKeys(): SshKeyManager
    {
        return $this->getManager(SshKeyManager::class);
    }

    public function images(): ImageManager
    {
        return $this->getManager(ImageManager::class);
    }

    public function certificates(): CertificateManager
    {
        return $this->getManager(CertificateManager::class);
    }

    public function placementGroups(): PlacementGroupManager
    {
        return $this->getManager(PlacementGroupManager::class);
    }

    public function locations(): LocationManager
    {
        return $this->getManager(LocationManager::class);
    }

    public function datacenters(): DatacenterManager
    {
        return $this->getManager(DatacenterManager::class);
    }

    public function isos(): IsoManager
    {
        return $this->getManager(IsoManager::class);
    }

    public function pricing(): PricingManager
    {
        return $this->getManager(PricingManager::class);
    }

    public function actions(): ActionManager
    {
        return $this->getManager(ActionManager::class);
    }

    public function serverTypes(): ServerTypeManager
    {
        return $this->getManager(ServerTypeManager::class);
    }

    public function loadBalancerTypes(): LoadBalancerTypeManager
    {
        return $this->getManager(LoadBalancerTypeManager::class);
    }

    /**
     * Lazy instantiate and cache managers.
     */
    private function getManager(string $class)
    {
        if (! isset($this->managers[$class])) {
            $this->managers[$class] = new $class($this->client);
        }

        return $this->managers[$class];
    }
}
