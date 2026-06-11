<?php

namespace Vendor\HetznerCloud\DTOs;

class SshKey
{
    public int $id;

    public string $name;

    public string $fingerprint;

    public string $publicKey;

    public string $created;

    public array $labels = [];

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $key = new self;
        $key->id = (int) ($data['id'] ?? 0);
        $key->name = (string) ($data['name'] ?? '');
        $key->fingerprint = (string) ($data['fingerprint'] ?? '');
        $key->publicKey = (string) ($data['public_key'] ?? '');
        $key->created = (string) ($data['created'] ?? '');
        $key->labels = (array) ($data['labels'] ?? []);

        return $key;
    }
}
