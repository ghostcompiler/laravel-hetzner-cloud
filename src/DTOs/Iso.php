<?php

namespace Vendor\HetznerCloud\DTOs;

class Iso
{
    public int $id;
    public string $name;
    public string $description;
    public string $type;
    public ?string $deprecated = null;

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $iso = new self();
        $iso->id = (int) ($data['id'] ?? 0);
        $iso->name = (string) ($data['name'] ?? '');
        $iso->description = (string) ($data['description'] ?? '');
        $iso->type = (string) ($data['type'] ?? '');
        $iso->deprecated = isset($data['deprecated']) ? (string) $data['deprecated'] : null;
        return $iso;
    }
}
