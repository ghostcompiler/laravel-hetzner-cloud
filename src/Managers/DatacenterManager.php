<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\DatacenterCollection;
use Vendor\HetznerCloud\DTOs\Datacenter;

class DatacenterManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('datacenters', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $dcs = array_map(fn (array $item) => Datacenter::fromArray($item), $data['datacenters'] ?? []);

            return new DatacenterCollection($dcs);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function find(int $id)
    {
        $response = $this->getRequest("datacenters/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Datacenter::fromArray($data['datacenter'] ?? []);
        });
    }
}
