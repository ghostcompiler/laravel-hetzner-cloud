<?php

namespace Vendor\HetznerCloud\DTOs;

class PaginationMeta
{
    public int $page;

    public int $perPage;

    public ?int $previousPage;

    public ?int $nextPage;

    public int $lastPage;

    public int $totalEntries;

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $meta = new self;
        $meta->page = (int) ($data['page'] ?? 1);
        $meta->perPage = (int) ($data['per_page'] ?? 25);
        $meta->previousPage = isset($data['previous_page']) ? (int) $data['previous_page'] : null;
        $meta->nextPage = isset($data['next_page']) ? (int) $data['next_page'] : null;
        $meta->lastPage = (int) ($data['last_page'] ?? 1);
        $meta->totalEntries = (int) ($data['total_entries'] ?? 0);

        return $meta;
    }
}
