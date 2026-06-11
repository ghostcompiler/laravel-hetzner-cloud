<?php

namespace Vendor\HetznerCloud\Responses;

use Vendor\HetznerCloud\DTOs\Action;
use Vendor\HetznerCloud\DTOs\Volume;

class VolumeCreateResponse
{
    public Volume $volume;

    public ?Action $action = null;

    public function __construct(Volume $volume, ?Action $action = null)
    {
        $this->volume = $volume;
        $this->action = $action;
    }

    /**
     * Create response wrapper from raw API data.
     */
    public static function fromArray(array $data): self
    {
        $volume = Volume::fromArray($data['volume'] ?? []);
        $action = isset($data['action']) ? Action::fromArray($data['action']) : null;

        return new self($volume, $action);
    }
}
