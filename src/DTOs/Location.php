<?php

namespace Vendor\HetznerCloud\DTOs;

class Location
{
    public int $id;

    public string $name;

    public string $description;

    public string $country;

    public string $city;

    public float $latitude;

    public float $longitude;

    public string $networkZone;

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $loc = new self;
        $loc->id = (int) ($data['id'] ?? 0);
        $loc->name = (string) ($data['name'] ?? '');
        $loc->description = (string) ($data['description'] ?? '');
        $loc->country = (string) ($data['country'] ?? '');
        $loc->city = (string) ($data['city'] ?? '');
        $loc->latitude = (float) ($data['latitude'] ?? 0.0);
        $loc->longitude = (float) ($data['longitude'] ?? 0.0);
        $loc->networkZone = (string) ($data['network_zone'] ?? '');

        return $loc;
    }
}
