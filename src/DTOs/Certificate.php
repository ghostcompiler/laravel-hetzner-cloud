<?php

namespace Vendor\HetznerCloud\DTOs;

class Certificate
{
    public int $id;
    public string $name;
    public array $labels = [];
    public string $type;
    public string $created;
    public array $domainNames = [];
    public ?string $fingerprint = null;
    public ?array $status = null;
    public ?string $notBefore = null;
    public ?string $notAfter = null;

    /**
     * Create a new DTO instance from array data.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $cert = new self();
        $cert->id = (int) ($data['id'] ?? 0);
        $cert->name = (string) ($data['name'] ?? '');
        $cert->labels = (array) ($data['labels'] ?? []);
        $cert->type = (string) ($data['type'] ?? '');
        $cert->created = (string) ($data['created'] ?? '');
        $cert->domainNames = (array) ($data['domain_names'] ?? []);
        $cert->fingerprint = isset($data['fingerprint']) ? (string) $data['fingerprint'] : null;
        $cert->status = isset($data['status']) ? (array) $data['status'] : null;
        $cert->notBefore = isset($data['not_before']) ? (string) $data['not_before'] : null;
        $cert->notAfter = isset($data['not_after']) ? (string) $data['not_after'] : null;
        return $cert;
    }
}
