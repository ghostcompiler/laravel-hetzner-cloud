<?php

namespace Vendor\HetznerCloud\DTOs;

class Firewall
{
    public int $id;
    public string $name;
    public array $rules = [];
    public array $appliedTo = [];
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
        $firewall = new self();
        $firewall->id = (int) ($data['id'] ?? 0);
        $firewall->name = (string) ($data['name'] ?? '');
        $firewall->rules = (array) ($data['rules'] ?? []);
        $firewall->appliedTo = (array) ($data['applied_to'] ?? []);
        $firewall->created = (string) ($data['created'] ?? '');
        $firewall->labels = (array) ($data['labels'] ?? []);
        return $firewall;
    }
}
