<?php

namespace Vendor\HetznerCloud\DTOs;

class ServerType
{
    public int $id;

    public string $name;

    public string $description;

    public int $cores;

    public float $memory;

    public int $disk;

    public ?string $deprecated = null;

    public array $prices = [];

    public string $storageType;

    public string $cpuType;

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $type = new self;
        $type->id = (int) ($data['id'] ?? 0);
        $type->name = (string) ($data['name'] ?? '');
        $type->description = (string) ($data['description'] ?? '');
        $type->cores = (int) ($data['cores'] ?? 0);
        $type->memory = (float) ($data['memory'] ?? 0.0);
        $type->disk = (int) ($data['disk'] ?? 0);
        $type->deprecated = isset($data['deprecated']) ? (string) $data['deprecated'] : null;
        $type->prices = (array) ($data['prices'] ?? []);
        $type->storageType = (string) ($data['storage_type'] ?? '');
        $type->cpuType = (string) ($data['cpu_type'] ?? '');

        return $type;
    }
}
