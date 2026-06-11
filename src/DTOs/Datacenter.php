<?php

namespace Vendor\HetznerCloud\DTOs;

class Datacenter
{
    public int $id;

    public string $name;

    public string $description;

    public array $location = [];

    public array $serverTypes = [];

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $dc = new self;
        $dc->id = (int) ($data['id'] ?? 0);
        $dc->name = (string) ($data['name'] ?? '');
        $dc->description = (string) ($data['description'] ?? '');
        $dc->location = (array) ($data['location'] ?? []);
        $dc->serverTypes = (array) ($data['server_types'] ?? []);

        return $dc;
    }
}
