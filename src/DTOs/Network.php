<?php

namespace Vendor\HetznerCloud\DTOs;

class Network
{
    public int $id;
    public string $name;
    public string $ipRange;
    public array $subnets = [];
    public array $routes = [];
    public array $servers = [];
    public array $protection = [];
    public string $created;
    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $network = new self();
        $network->id = (int) ($data['id'] ?? 0);
        $network->name = (string) ($data['name'] ?? '');
        $network->ipRange = (string) ($data['ip_range'] ?? '');
        $network->subnets = (array) ($data['subnets'] ?? []);
        $network->routes = (array) ($data['routes'] ?? []);
        $network->servers = (array) ($data['servers'] ?? []);
        $network->protection = (array) ($data['protection'] ?? []);
        $network->created = (string) ($data['created'] ?? '');
        $network->labels = (array) ($data['labels'] ?? []);
        return $network;
    }
}
