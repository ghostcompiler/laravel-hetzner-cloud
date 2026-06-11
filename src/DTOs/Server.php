<?php

namespace Vendor\HetznerCloud\DTOs;

class Server
{
    public int $id;
    public string $name;
    public string $status;
    public string $created;
    public array $publicNet = [];
    public array $privateNet = [];
    public array $serverType = [];
    public ?array $image = null;
    public array $datacenter = [];
    public array $volumes = [];
    public array $firewalls = [];
    public bool $rescueEnabled = false;
    public bool $locked = false;
    public ?string $backupWindow = null;
    public ?int $outgoingTraffic = null;
    public ?int $ingoingTraffic = null;
    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $server = new self();
        $server->id = (int) ($data['id'] ?? 0);
        $server->name = (string) ($data['name'] ?? '');
        $server->status = (string) ($data['status'] ?? '');
        $server->created = (string) ($data['created'] ?? '');
        $server->publicNet = (array) ($data['public_net'] ?? []);
        $server->privateNet = (array) ($data['private_net'] ?? []);
        $server->serverType = (array) ($data['server_type'] ?? []);
        $server->image = isset($data['image']) ? (array) $data['image'] : null;
        $server->datacenter = (array) ($data['datacenter'] ?? []);
        $server->volumes = (array) ($data['volumes'] ?? []);
        $server->firewalls = (array) ($data['firewalls'] ?? []);
        $server->rescueEnabled = (bool) ($data['rescue_enabled'] ?? false);
        $server->locked = (bool) ($data['locked'] ?? false);
        $server->backupWindow = isset($data['backup_window']) ? (string) $data['backup_window'] : null;
        $server->outgoingTraffic = isset($data['outgoing_traffic']) ? (int) $data['outgoing_traffic'] : null;
        $server->ingoingTraffic = isset($data['ingoing_traffic']) ? (int) $data['ingoing_traffic'] : null;
        $server->labels = (array) ($data['labels'] ?? []);
        return $server;
    }
}
