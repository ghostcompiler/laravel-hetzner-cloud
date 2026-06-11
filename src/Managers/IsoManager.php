<?php

namespace Vendor\HetznerCloud\Managers;

use Vendor\HetznerCloud\Collections\IsoCollection;
use Vendor\HetznerCloud\DTOs\Iso;

class IsoManager extends AbstractManager
{
    public function all()
    {
        $response = $this->getRequest('isos', $this->buildQueryParams());

        return $this->hydrate($response, function (array $data) {
            $isos = array_map(fn (array $item) => Iso::fromArray($item), $data['isos'] ?? []);

            return new IsoCollection($isos);
        });
    }

    public function get()
    {
        return $this->all();
    }

    public function find(int $id)
    {
        $response = $this->getRequest("isos/{$id}");

        return $this->hydrate($response, function (array $data) {
            return Iso::fromArray($data['iso'] ?? []);
        });
    }
}
