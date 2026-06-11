<?php

namespace Vendor\HetznerCloud\DTOs;

class LoadBalancerType
{
    public int $id;
    public string $name;
    public string $description;
    public ?string $deprecated = null;
    public array $prices = [];
    public int $maxConnections;
    public int $maxServices;
    public int $maxTargets;

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $type = new self();
        $type->id = (int) ($data['id'] ?? 0);
        $type->name = (string) ($data['name'] ?? '');
        $type->description = (string) ($data['description'] ?? '');
        $type->deprecated = isset($data['deprecated']) ? (string) $data['deprecated'] : null;
        $type->prices = (array) ($data['prices'] ?? []);
        $type->maxConnections = (int) ($data['max_connections'] ?? 0);
        $type->maxServices = (int) ($data['max_services'] ?? 0);
        $type->maxTargets = (int) ($data['max_targets'] ?? 0);
        return $type;
    }
}
