<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\LocationCollection;
use Vendor\HetznerCloud\DTOs\Location;

class LocationManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('locations', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $locs = array_map(fn (array $item) => Location::fromArray($item), $data['locations'] ?? []);

            return new LocationCollection($locs);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function find(int $id)
    {
        $response = $this->getRequest("locations/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Location::fromArray($data['location'] ?? []);
        });
    }
}
