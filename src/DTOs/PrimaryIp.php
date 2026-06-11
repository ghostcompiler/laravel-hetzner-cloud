<?php

namespace Vendor\HetznerCloud\DTOs;

class PrimaryIp
{
    public int $id;
    public string $name;
    public string $ip;
    public string $type;
    public ?int $assigneeId = null;
    public string $assigneeType;
    public array $datacenter = [];
    public array $dnsPtr = [];
    public bool $blocked = false;
    public string $created;
    public array $protection = [];
    public array $labels = [];
    public bool $autoDelete = false;

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $ip = new self();
        $ip->id = (int) ($data['id'] ?? 0);
        $ip->name = (string) ($data['name'] ?? '');
        $ip->ip = (string) ($data['ip'] ?? '');
        $ip->type = (string) ($data['type'] ?? '');
        $ip->assigneeId = isset($data['assignee_id']) ? (int) $data['assignee_id'] : null;
        $ip->assigneeType = (string) ($data['assignee_type'] ?? '');
        $ip->datacenter = (array) ($data['datacenter'] ?? []);
        $ip->dnsPtr = (array) ($data['dns_ptr'] ?? []);
        $ip->blocked = (bool) ($data['blocked'] ?? false);
        $ip->created = (string) ($data['created'] ?? '');
        $ip->protection = (array) ($data['protection'] ?? []);
        $ip->labels = (array) ($data['labels'] ?? []);
        $ip->autoDelete = (bool) ($data['auto_delete'] ?? false);
        return $ip;
    }
}
