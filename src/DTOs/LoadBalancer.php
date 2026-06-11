<?php

namespace Vendor\HetznerCloud\DTOs;

class LoadBalancer
{
    public int $id;
    public string $name;
    public array $publicNet = [];
    public array $privateNet = [];
    public array $loadBalancerType = [];
    public array $location = [];
    public array $algorithm = [];
    public array $services = [];
    public array $targets = [];
    public string $created;
    public array $protection = [];
    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $lb = new self();
        $lb->id = (int) ($data['id'] ?? 0);
        $lb->name = (string) ($data['name'] ?? '');
        $lb->publicNet = (array) ($data['public_net'] ?? []);
        $lb->privateNet = (array) ($data['private_net'] ?? []);
        $lb->loadBalancerType = (array) ($data['load_balancer_type'] ?? []);
        $lb->location = (array) ($data['location'] ?? []);
        $lb->algorithm = (array) ($data['algorithm'] ?? []);
        $lb->services = (array) ($data['services'] ?? []);
        $lb->targets = (array) ($data['targets'] ?? []);
        $lb->created = (string) ($data['created'] ?? '');
        $lb->protection = (array) ($data['protection'] ?? []);
        $lb->labels = (array) ($data['labels'] ?? []);
        return $lb;
    }
}
