<?php

namespace Vendor\HetznerCloud\DTOs;

class FloatingIp
{
    public int $id;

    public string $name;

    public string $ip;

    public string $type;

    public ?int $server = null;

    public string $description;

    public array $dnsPtr = [];

    public bool $blocked = false;

    public array $homeLocation = [];

    public string $created;

    public array $protection = [];

    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $ip = new self;
        $ip->id = (int) ($data['id'] ?? 0);
        $ip->name = (string) ($data['name'] ?? '');
        $ip->ip = (string) ($data['ip'] ?? '');
        $ip->type = (string) ($data['type'] ?? '');
        $ip->server = isset($data['server']) ? (int) $data['server'] : null;
        $ip->description = (string) ($data['description'] ?? '');
        $ip->dnsPtr = (array) ($data['dns_ptr'] ?? []);
        $ip->blocked = (bool) ($data['blocked'] ?? false);
        $ip->homeLocation = (array) ($data['home_location'] ?? []);
        $ip->created = (string) ($data['created'] ?? '');
        $ip->protection = (array) ($data['protection'] ?? []);
        $ip->labels = (array) ($data['labels'] ?? []);

        return $ip;
    }
}
