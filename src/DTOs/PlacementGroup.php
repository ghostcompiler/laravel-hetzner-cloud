<?php

namespace Vendor\HetznerCloud\DTOs;

class PlacementGroup
{
    public int $id;
    public string $name;
    public string $type;
    public array $servers = [];
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
        $group = new self();
        $group->id = (int) ($data['id'] ?? 0);
        $group->name = (string) ($data['name'] ?? '');
        $group->type = (string) ($data['type'] ?? '');
        $group->servers = (array) ($data['servers'] ?? []);
        $group->created = (string) ($data['created'] ?? '');
        $group->labels = (array) ($data['labels'] ?? []);
        return $group;
    }
}
