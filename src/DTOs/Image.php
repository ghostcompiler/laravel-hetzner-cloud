<?php

namespace Vendor\HetznerCloud\DTOs;

class Image
{
    public int $id;
    public string $type;
    public string $status;
    public ?string $name = null;
    public string $description;
    public ?float $imageSize = null;
    public float $diskSize;
    public string $created;
    public ?array $createdFrom = null;
    public ?string $boundTo = null;
    public ?string $osFlavor = null;
    public ?string $osVersion = null;
    public bool $rapidRebuild = false;
    public array $protection = [];
    public ?string $deprecated = null;
    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $image = new self();
        $image->id = (int) ($data['id'] ?? 0);
        $image->type = (string) ($data['type'] ?? '');
        $image->status = (string) ($data['status'] ?? '');
        $image->name = isset($data['name']) ? (string) $data['name'] : null;
        $image->description = (string) ($data['description'] ?? '');
        $image->imageSize = isset($data['image_size']) ? (float) $data['image_size'] : null;
        $image->diskSize = (float) ($data['disk_size'] ?? 0);
        $image->created = (string) ($data['created'] ?? '');
        $image->createdFrom = isset($data['created_from']) ? (array) $data['created_from'] : null;
        $image->boundTo = isset($data['bound_to']) ? (string) $data['bound_to'] : null;
        $image->osFlavor = isset($data['os_flavor']) ? (string) $data['os_flavor'] : null;
        $image->osVersion = isset($data['os_version']) ? (string) $data['os_version'] : null;
        $image->rapidRebuild = (bool) ($data['rapid_rebuild'] ?? false);
        $image->protection = (array) ($data['protection'] ?? []);
        $image->deprecated = isset($data['deprecated']) ? (string) $data['deprecated'] : null;
        $image->labels = (array) ($data['labels'] ?? []);
        return $image;
    }
}
