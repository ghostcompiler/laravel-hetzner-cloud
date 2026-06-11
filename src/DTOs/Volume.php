<?php

namespace Vendor\HetznerCloud\DTOs;

class Volume
{
    public int $id;
    public string $name;
    public int $size;
    public string $status;
    public ?int $server = null;
    public array $location = [];
    public string $created;
    public array $protection = [];
    public array $labels = [];
    public string $linuxDevice;

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $volume = new self();
        $volume->id = (int) ($data['id'] ?? 0);
        $volume->name = (string) ($data['name'] ?? '');
        $volume->size = (int) ($data['size'] ?? 0);
        $volume->status = (string) ($data['status'] ?? '');
        $volume->server = isset($data['server']) ? (int) $data['server'] : null;
        $volume->location = (array) ($data['location'] ?? []);
        $volume->created = (string) ($data['created'] ?? '');
        $volume->protection = (array) ($data['protection'] ?? []);
        $volume->labels = (array) ($data['labels'] ?? []);
        $volume->linuxDevice = (string) ($data['linux_device'] ?? '');
        return $volume;
    }
}
