<?php

namespace Vendor\HetznerCloud\Responses;

use Illuminate\Support\Collection;
use Vendor\HetznerCloud\DTOs\PaginationMeta;

class PaginatedResponse
{
    /** @var Collection */
    public Collection $items;
    public PaginationMeta $pagination;

    public function __construct(Collection $items, PaginationMeta $pagination)
    {
        $this->items = $items;
        $this->pagination = $pagination;
    }
}
